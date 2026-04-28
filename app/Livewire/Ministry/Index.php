<?php

namespace App\Livewire\Ministry;

use Livewire\Component;
use App\Models\Ministry;
use App\Models\User;
use App\Models\Event;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Flux\Flux;
use App\Notifications\Invitation;
use Livewire\Attributes\Validate;

class Index extends Component
{

    public Ministry $ministry;

    public User $user;

    public $total_decisions = 0;
    public $gospel_shares = 0;

    public function mount() {
        $this->user = Auth::user();
        $this->ministry = $this->user->ministry;
        $decison1 = $this->ministry->decisions->sum('number_of_decisions');
        $decision2 = $this->ministry->contacts->where('decision', true)->count();
        $this->total_decisions = $decison1 + $decision2;

        $this->gospel_shares = $this->ministry->gospelShares->sum('number_of_gospel_shares') + $this->ministry->contacts->count() + $this->ministry->decisions->sum('number_of_decisions');

        // if($this->user->role === 'follow_up') {
        //     $this->redirect(route('events.show', [$ministry, $this->user->event]));
        // }
        // if(in_array($this->user->role === ['pastor', 'ambassador', 'church_member'])) {
        //     $this->redirect(route('events.show', [$ministry, $this->user->event]));
        // }
        // $this->authorize('view', $this->ministry);
    }

    public function newContacts($event) {
        $newContacts = Contact::where('event_id' , $event->id)->where('assigned', false)->first();
        return $newContacts;
    }

    public function getDecisions($event) {
        $contactsWithDecisions = Contact::where('event_id' , $event->id)->where('decision', true)->count();
        $decisionsWithoutContactDetails = $event->decisions_without_contact_details;
        return $contactsWithDecisions + $decisionsWithoutContactDetails;
    }

    public function showEvent($event) {
        return $this->redirect(route('events.show', [$this->ministry, $event['slug']]), navigate: true);
    }
    
    public function render()
    {
        return view('livewire.ministry.index');
    }
}


