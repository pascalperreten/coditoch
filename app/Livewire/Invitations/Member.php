<?php

namespace App\Livewire\Invitations;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Models\Church;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;

#[Layout('components.layouts.auth')]
class Member extends Component
{
    #[Validate('required|string|max:255')]
    public $first_name;

    #[Validate('required|string|max:255')]
    public $last_name;

    #[Validate('required|email|max:255')]
    public $email;

    #[Validate('required|string|min:8|confirmed')]
    public $password;

    #[Validate('required')]
    public $password_confirmation;

    public ?Church $church;

    public $invitation_token;

    public $user;

    public function mount($token) {
        $this->user = User::where('invitation_token', $token)->firstOrFail();
        $this->first_name = $this->user->first_name;
        $this->last_name = $this->user->last_name;
        $this->email = $this->user->email;
    }


    public function save () {

        $this->invitation_token = null;
        $this->validate();

        $this->user->update($this->only(['password', 'invitation_token']));

        Auth::login($this->user);
        $this->user->sendEmailVerificationNotification();

        
        if($this->user->church_id) {
            if ($this->user->church->members()->count() <= 1) {
                $this->user->church->update(['follow_up_contact' => $this->user->id]);
                $this->dispatch('newChurchAdded');
                return $this->redirect(route('churches.manage', [$this->user->church->event->ministry, $this->user->church->event, $this->user->church]), navigate: true);
            } else {
                return $this->redirect(route('churches.show',[$this->user->church->event->ministry, $this->user->church->event, $this->user->church]), navigate: true);
            }
        } elseif ($this->user->role === 'follow_up') { 
            $event = $this->user->events()->first();
            return $this->redirect(route('events.show', [$this->user->event->ministry, $event]), navigate: true);
        } else {
            return $this->redirect(route('dashboard'), navigate: true);
        }
        
    }
    

    public function render()
    {
        return view('livewire.invitations.member');
    }
}
