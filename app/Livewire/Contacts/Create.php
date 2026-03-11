<?php

namespace App\Livewire\Contacts;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Livewire\Forms\ContactForm;
use App\Livewire\Forms\DistrictForm;
use App\Livewire\Forms\LanguageForm;
use App\Livewire\Forms\PostalCodeForm;
use App\Models\Event;
use App\Models\Church;
use App\Models\Ministry;
use App\Models\Decision;
use Flux\Flux;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;
use Illuminate\Support\Arr;


class Create extends Component
{

    public ContactForm $form;
    public DistrictForm $district_form;
    public LanguageForm $language_form;
    public PostalCodeForm $postal_code_form;

    public Event $event;
    public Ministry $ministry;
    public ?Church $church = null;

    public $tab = "with_contact";

    public $success_message = '';

    public bool $approved = false;
    public bool $contact_selected = false;

    public function mount(Ministry $ministry, Event $event, Church $church = null) {
        $this->ministry = $ministry;
        $this->event = $event;
        $this->church = $church;
        $this->language_form->setLanguages($this->event);
        $this->district_form->setDistricts($this->event);
        $this->postal_code_form->setPostalCodes($this->event);

        $this->form->setContactForm($this->event);

    }

    public function newContact() {
        $this->success_message = '';
    }

    public function nextPage() {
        $this->approved = true;
    }

    public function lastPage() {
        $this->approved = false;
    }

    public function resetNumbers() {
        $this->reset('tab');
    }
    public function resetContact() {
        $this->approved = false;
        $this->form->reset();
        $this->form->setContactForm($this->event);
    }

    public function save() {
        $this->form->create($this->event, $this->church);
        $this->district_form->name = '';
        $this->postal_code_form->name = '';
        $this->form->setContactForm($this->event);
        $this->approved = false;
        $this->success_message=__('Contact added');
    }
    public function addDecisions() {
        $this->form->addDecisions($this->event);
        $this->form->setContactForm($this->event);
        $this->page = 0;
        $this->success_message=__('Decisions added');
    }

    public function createDistrict()
    {
        $this->district_form->event = $this->event;
        $newDistrict = $this->district_form->create();
        $this->district_form->setDistricts($this->event);
        $this->form->districts[] = $newDistrict->id;
        $this->form->district = $newDistrict->id;
    }

    public function createPostalCode() {
        $this->postal_code_form->event = $this->event;
        $newPostalCode = $this->postal_code_form->addPostalCode();
        $this->postal_code_form->setPostalCodes($this->event);
        $this->form->postal_codes[] = $newPostalCode->id;
        $this->form->postal_code = $newPostalCode->id;
    }

    // Functions to show or hide elements
    public function showDistrict() {
        return $this->form->form_fields->district &&  $this->form->unknown_postal_code;
    }

    public function showPostalCode() {
        return $this->form->form_fields->postal_code && !$this->form->unknown_postal_code;
    }
    public function showNotForeignCity() {
        return isset($this->form->foreign_city) && !$this->form->foreign_city;
    }

    public function showCheckboxPostalCode() {
        return $this->form->form_fields->postal_code && $this->form->form_fields->district;
    }

    public function render()
    {
        return view('livewire.contacts.create');
    }
}
