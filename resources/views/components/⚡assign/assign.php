<?php

use Livewire\Component;
use Flux\Flux;
use App\Models\Contact;
use App\Models\Ministry;
use App\Models\Event;
use App\Models\Church;
use App\Models\ManageFollowUp;
use Illuminate\Database\Eloquent\Builder;
use App\Notifications\ContactAdded;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;

new class extends Component
{

    public Event $event;
    public Ministry $ministry;
    public $class = '';
    public $church_name;
    public $plzChurches = [];
    public $districtChurches = [];
    public $languageChurches = [];
    public ?Contact $currentContact = null;
    public $showChurches = false;
    //public $newContacts = [];
    //public $newForeignContacts;
    public $withChurches = [];
    public $form_fields;

    
    public function mount(Ministry $ministry, Event $event) {
        $this->event = $event;
        $this->form_fields = ManageFollowUp::where('event_id', $event->id)->first();
        //$this->setNewForeignContacts();
    }

    public $sortBy = 'created_at';
    public $sortDirection = 'asc';

    public function sort($column) {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function postalCodeUrl($contact)
    {
        $address = urlencode($contact->postalCode->first()->name . ', ' . $contact->city);
        return "https://www.google.com/maps/search/?api=1&query={$address}";
    }
    public function districtUrl($contact)
    {
        $address = urlencode($contact->district->first()->name . ', ' . $contact->city);
        return "https://www.google.com/maps/search/?api=1&query={$address}";
    }

    public function getContactNumber($church_id) {
        $church = Church::findOrFail($church_id);
        return $church->contacts()->where('assigned', true)->count();
    }

    #[Computed]
    public function newContacts() {
        return Contact::query()
        ->with(['postalCode', 'district', 'languages'])
        ->withAggregate('postalCode', 'name')
        ->withAggregate('district', 'name')
        ->where('foreign_city', false)
        ->orderBy($this->sortBy, $this->sortDirection)
        ->where('assigned', false)
        ->where('event_id', $this->event->id)
        ->get();
    }

    #[Computed]
    public function newForeignContacts() {
        return Contact::where('assigned', false)->where('event_id', $this->event->id)->where('foreign_city', true)->get();
    }

    public function closeAndResetChurches() {
        $this->showChurches = false;
        $this->currentContact = null;
        $this->plzChurches = [];
        $this->districtChurches = [];
        $this->languageChurches = [];
    }

    public function checkChurches($id) {
        $this->showChurches = true;
        $this->currentContact = Contact::findOrFail($id);
        $this->plzChurches = $this->checkPlz($this->currentContact);
        $this->districtChurches = $this->checkDistrict($this->currentContact);
        $this->languageChurches = $this->checkLanguage($this->currentContact);
    }

    public function checkPlz($contact) {
        $postalCodesId = $contact->postalCode()->pluck('postal_codes.id');
        $churches = Church::whereHas('postalCodes', function (Builder $query) use ($postalCodesId) {
            $query->whereIn('postal_codes.id', $postalCodesId);
        })->get();
        return $churches;
    }

    public function checkDistrict($contact) {
        $districtsIds = $contact->district()->pluck('districts.id');
        $churches = Church::whereHas('districts', function (Builder $query) use ($districtsIds) {
            $query->whereIn('districts.id', $districtsIds);
        })->get();
        return $churches;
    }

    public function checkLanguage($contact) {
        $languagesIds = $contact->languages()->pluck('languages.id');
        $churches = Church::whereHas('languages', function (Builder $query) use ($languagesIds) {
            $query->whereIn('languages.id', $languagesIds);
        })->get();
        return $churches;
    }

    public function assignChurchName($id) {
        $this->validate([     
            'church_name' => 'required|string|max:255',
        ]);
        $contact = Contact::findOrFail($id);
        $contact->update([
            'assigned' => true,
            'church_name' => $this->church_name,
        ]);
        Flux::modals()->close();

        Flux::toast(
            heading: __('Contact assigned'),
            text: __('The contact has been successfully assigned to the church.'),
            variant: 'success',
        );
    }

    public function updateChurch() {
        $churchesWithContacts = Contact::where('assigned', false)->whereNotNull('church_id')->where('event_id', $this->event->id)->pluck('church_id')->toArray();
        foreach(array_unique($churchesWithContacts) as $churchId) {
            $church = Church::findOrFail($churchId);
            if($church->followUpContact) {
                $church->followUpContact->notify(new ContactAdded($this->event->ministry, $this->event, $church));
            }
        }
        if(!empty($churchesWithContacts)) {
            foreach ($this->newContacts->whereNotNull('church_id') as $contact) {
                $contact->update([
                    'assigned' => true,
                ]);
            }
            Flux::toast(
                heading: __('Changes saved'),
                text: __('Your changes have been saved successfully.'),
                variant: 'success',
            );
        }
        Flux::modal('assign-church')->close();
    }
};