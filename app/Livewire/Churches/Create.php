<?php

namespace App\Livewire\Churches;

use Livewire\Component;
use App\Models\Event;
use App\Models\Ministry;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class Create extends Component
{
    public Ministry $ministry;
    public Event $event;
    public bool $active_invitation_link;
    public $invitation_token;
    public $time;
    public $type;
    public $signedRoute = 'not-temporary';

    public function mount (Ministry $ministry, Event $event) {
        $this->ministry = $ministry;
        $this->event = $event;
        $this->active_invitation_link = $this->event->active_invitation_link;
        //$this->invitation_token = Event::where('invitation_token', $invitation_token)->firstOrFail();
    } 

    public function updatedActiveInvitationLink($value) {
        $this->event->update(['active_invitation_link' => $value]);
    }

    public function addChurchLink() {
        return URL::signedRoute('register.storeUserAndChurch', [$this->ministry, $this->event, $this->event->invitation_token]);
    }

    public function render()
    {
        return view('livewire.churches.create');
    }
}
