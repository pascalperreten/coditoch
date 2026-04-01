<?php

namespace App\Livewire\Contacts;

use Livewire\Component;
use App\Livewire\Forms\ContactForm;
use App\Models\Event;
use App\Models\Ministry;
use App\Models\Contact;
use Flux\Flux;

class Edit extends Component
{
    public Ministry $ministry;
    public Event $event;
    public Contact $contact;

    public $newCity = '';

    public $church_name = '';
    
    public ContactForm $form;

    public function mount(Ministry $ministry, Event $event, Contact $contact) {
        $this->ministry = $ministry;
        $this->event = $event;
        $this->contact = $contact;
        $this->church_name = $contact->church_name;
    }

    public function save() {
        $this->form->update($this->contact);
    }

    public function livesInAnotherCity() {
        
         $this->validate([
            'newCity' => 'required|string|max:255',
        ]);

        $this->contact->update([
            'foreign_city' => true,
            'city' => $this->newCity,
            'church_id' => null,
            'assigned' => false,
        ]);
        Flux::modal('lives-in-another-city')->close();
    }

    public function livesInThisCity() {
        $this->contact->update([
            'foreign_city' => false,
            'city' => $this->event->city,
            'church_name' => null,
            'assigned' => false,
        ]);
        Flux::modal('lives-in-this-city')->close();
    }

    public function updateChurch() {
        $this->validate([
            'church_name' => 'required|string|max:255',
        ]);

        $this->contact->update([
            'church_name' => $this->church_name,
            'assigned' => true,
        ]);
    }

    public function changeChurch() {
        $this->contact->update([
            'church_id' => null,
            'assigned' => false,
        ]);
        $this->redirect(route('events.show', ['ministry' => $this->ministry, 'event' => $this->event]), navigate: true);
    }

    public function delete() {
        $this->contact->delete();
        return $this->redirect(route('contacts.index', ['ministry' => $this->ministry, 'event' => $this->event]), navigate: true);
    }

    public function render()
    {
        return view('livewire.contacts.edit');
    }
}
