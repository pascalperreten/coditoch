<?php

namespace App\Livewire\Churches;

use Livewire\Component;
use App\Models\Ministry;
use App\Models\Event;
use App\Models\Church;
use App\Models\User;
use App\Models\ManageFollowUp;
use Illuminate\Support\Facades\URL;
use Livewire\Attributes\On;
use Flux\Flux;

class Manage extends Component
{
    public Ministry $ministry;
    public Event $event;
    public Church $church;
    public bool $evangelize;
    public $members;
    public $follow_up_contact;

    public function mount(Ministry $ministry, Event $event, Church $church) {
        $this->ministry = $ministry;
        $this->event = $event;
        $this->church = $church;
        $this->setFollowUpContact();
        $this->evangelize = ManageFollowUp::where('event_id', $this->event->id)->pluck('church_evangelize')->first();
        $this->getMembers();
    }

    public function setFollowUpContact() {
        if($this->church->followUpContact) {
            $this->follow_up_contact = $this->church->followUpContact->id;
        }
    }

    public function followUpContacts() {
        return $this->church->members->where('role', 'ambassador')->sortBy('first_name');
    }

    public function updatedFollowUpContact() {
        $this->church->update([
            'follow_up_contact' => $this->follow_up_contact,
        ]);
        Flux::toast(
            heading: __('Saved'),
            text: __('Your changes have been saved successfully.'),
            variant: 'success',
        );
    }

    public function addContact() {
        return URL::signedRoute('churches.evangelize', [$this->ministry, $this->event, $this->church]);
    }

    #[On('invitation_sent')]
    public function getMembers() {
        $this->members = User::where('church_id', $this->church->id)->get();
    }

    public function render()
    {
        return view('livewire.churches.manage');
    }
}
