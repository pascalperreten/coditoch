<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\User;
use App\Models\Church;
use App\Models\Event;
use Illuminate\Support\Str;
use App\Notifications\Invitation;
use Illuminate\Validation\Rule;

class MemberForm extends Form
{
    public ?User $member;
    public ?Church $church = null;
    public ?Event $event = null;

    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $role;
    public $church_name;
    public $church_name_rule = 'required|string|max:255';
    public $church_id = null;
    public $events = [];

    public function rules() {
        $unique = Rule::unique('users', 'email');
        if (isset($this->member->id)) {
            $unique->ignore($this->member->id);
        }
        $event = '';
        if ($this->role === 'follow_up') {
            $event = 'required|array';
        }
        if($this->event) {
            $church_name_rule = [
                'required',
                'string',
                'max:255',
                Rule::unique('churches', 'name')->where('event_id', $this->event->id),
                ];
        } else {
            $church_name_rule = [
                'nullable',
                'string',
                'max:255',
                ];
        }

        return [
            'email' => [
                    'required',
                    'email',
                    'max:255',
                    $unique,
            ],
            'first_name' =>'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|max:255',
            'church_name' => $church_name_rule,
            'role' => 'required|in:admin,editor,follow_up,pastor,ambassador,church_member',
            'events' => $event,
            ];
    }

    public function setMember($member) {
        $this->member = $member;
        $this->first_name = $member->first_name;
        $this->last_name = $member->last_name;
        $this->email = $member->email;
        $this->phone = $member->phone;
        $this->role = $member->role;
        $this->events = $member->events()->pluck('events.id')->toArray();
    }

    public function inviteChurch($event) {
        $this->event = $event;

        $this->validate();
        $church = Church::create([
            'name' => $this->church_name,
            'city' => '',
            'postal_code' => '',
            'street' => '',
            'event_id' => $event->id,
            'slug' => Str::slug($this->church_name),
        ]);

        $church->events()->attach($event->id);

        $newMember = User::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
            'church_id' => $church->id,
            'invitation_token' => Str::random(32),
        ]);

        $church->update([
            'follow_up_contact' => $newMember->id,
        ]);


        $this->reset('first_name', 'last_name', 'email', 'phone', 'role', 'church_name');

        // Logic to send invitation
        $newMember->notify(new Invitation($newMember, $event->ministry, $event));
    }

    public function create($ministry, $church, $event = null) {

        $ministry_id = $ministry->id;
        if($church) {
            $this->church_name_rule = 'nullable|string|max:255';
            $ministry_id = null;
        }

        $this->validate();

        if($church) {
            $this->church_id = $church->id;
        }
        $newMember = User::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
            'ministry_id' => $ministry_id,
            'church_id' => $this->church_id,
            'invitation_token' => Str::random(32),
        ]);

        $newMember->events()->sync($this->events);

        if($church && !$church->followUpContact) {
            $church->update([
                'follow_up_contact' => $newMember->id,
            ]);
        }

        // Logic to send invitation
        $newMember->notify(new Invitation($newMember, $ministry, $event));
    }

    public function validateOnlyStep(array $fields)
    {
        $rules = collect($this->rules)
            ->only($fields)
            ->toArray();
        $this->validate($rules);
    }

    public function update($church = null) {

        if($church) {
            $this->church_name_rule = 'nullable|string|max:255';
        }

        if($this->member->ministry && $this->member->ministry->user_id === $this->member->id) {
            $rules = collect($this->rules())
            ->only([
                'first_name',
                'last_name',
                'email',
                'phone',
            ])
            ->toArray();
            $this->validate($rules);
        } else {
            $this->validate($this->rules());
            
        }   
        if($this->member->events->count() > 0 && $this->role !== 'follow_up') {
            $this->events = [];
        }
        
        $this->member->update($this->only(['first_name', 'last_name', 'email', 'phone', 'role']));
        $this->member->events()->sync($this->events);
    }

    public function delete() {
        if($this->member->church && $this->member->church->followUpContact->id === $this->member->id) {
            $newFollowUpContact;
            if ($this->member->church->members()->where('role', 'ambassador')->exists()) {
                $newFollowUpContact = $this->member->church->members->where('role', 'ambassador')->first()->id;
            } elseif($this->member->church->members()->where('role', 'pastor')->exists()) {
                $newFollowUpContact = $this->member->church->members->where('role', 'pastor')->first()->id;
            } elseif($this->member->church->members) {
                $newFollowUpContact = $this->member->church->members->first()->id;
            } else {
                $newFollowUpContact = null;
            }
            
                $this->member->church->update([
                    'follow_up_contact' => $newFollowUpContact,
                ]);
            
        }
        $this->member->delete();
        $this->member = null;
    }

}
