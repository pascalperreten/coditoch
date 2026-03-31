<?php

namespace App\Livewire\Invitations;

use Livewire\Component;
use App\Models\Event;
use App\Models\Ministry;
use App\Models\Church as ChurchModel;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Actions\Fortify\CreateNewUserAndChurch;

#[Layout('layouts.auth')]
class Church extends Component
{
    public $event;
    public $ministry;
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $role;
    public $password;
    public $password_confirmation;
    public $church_name;
    public $token;
    

    public function mount(Ministry $ministry, Event $event, $token)
    {
        $this->event = $event;
        $this->ministry = $ministry;
        $this->token = $token;
        if (!$this->event->active_invitation_link) {
            abort(404);
        }
        Event::where('invitation_token', $token)->firstOrFail();
        $this->role = (string) old('role', $user->role ?? '');
    }

    public function registerChurch()
    {

        $user = app(CreateNewUserAndChurch::class)->create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'role' => $this->role,
            'phone' => $this->phone,
            'church_name' => $this->church_name,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
            'event_id' => $this->event->id,
        ]);

        //return redirect()->route('churches.manage', [$this->ministry, $this->event, $church], navigate: true);
    }
    public function render()
    {
        return view('livewire.invitations.church');
    }
}
