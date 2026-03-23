<?php

namespace App\Livewire\Members;

use Livewire\Component;
use App\Models\Ministry;
use App\Models\Church;
use App\Models\Event;
use Flux\Flux;
use App\Livewire\Forms\MemberForm;

class Create extends Component
{
    public Ministry $ministry;
    public ?Church $church = null;
    public ?Event $event = null;
    public MemberForm $form;

    public $events;

    public bool $churchInvitation = false;

    public $title;

    public function mount() {
        $this->events = Event::where('ministry_id', $this->ministry->id)->get();
    }

    public function sendInvitation()
    {
        if(!$this->churchInvitation) {
            $this->form->create($this->ministry, $this->church, $this->event);
            session()->flash('success', __('Invitation sent!'));

            if($this->church?->id) {
                
                $this->redirect(route('churches.members', [$this->ministry, $this->event, $this->church]), navigate: true);
            } else {
                $this->redirect(route('ministry.members', [$this->ministry, $this->event, $this->church]), navigate: true);
            }
            

        } else {
            $this->form->inviteChurch($this->event);
            
            $this->modal('invite-member')->close();
            Flux::toast(
                heading: __('Invitation sent!'),
                text: __('An invitation has been sent to the new member.'),
                variant: 'success',
            );
        }
        
    }
    public function render()
    {
        return view('livewire.members.create');
    }
}
