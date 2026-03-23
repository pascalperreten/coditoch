<?php

namespace App\Livewire\Manage;

use Livewire\Component;
use App\Models\Event;
use App\Models\Ministry;
use App\Models\ManageFollowUp;

class FollowUp extends Component
{

    public Event $event;
    public Ministry $ministry;

    public ManageFollowUp $manage_follow_up;

    public bool $language;
    public bool $age;
    public bool $gender;
    public bool $evangelist_name;
    public bool $location;


    public function mount(Ministry $ministry, Event $event) {
        $this->ministry = $ministry;
        $this->event = $event;
            
        $this->manage_follow_up = ManageFollowUp::where('event_id', $this->event->id)->first();
        $this->language = $this->manage_follow_up->language;
        $this->age = $this->manage_follow_up->age;
        $this->gender = $this->manage_follow_up->gender;  
        $this->evangelist_name = $this->manage_follow_up->evangelist_name;  
        $this->location = $this->manage_follow_up->location;
        $this->event_id = $this->event->id;
    }

    public function updated($name, $value) {
        $this->manage_follow_up->update([
            $name => $value
        ]);
    } 
    public function render()
    {
        return view('livewire.manage.follow-up');
    }
}
