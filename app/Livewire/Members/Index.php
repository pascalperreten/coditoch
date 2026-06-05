<?php

namespace App\Livewire\Members;

use Livewire\Component;
use App\Models\Ministry;
use App\Models\User;
use App\Models\Church;
use App\Models\Event;
use Flux\Flux;
use App\Notifications\Invitation;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;

class Index extends Component
{
    public ?Event $event;
    public ?Church $church = null;
    public Ministry $ministry;

    public $members;

    #[On('updated')]
    public function refreshMembers() {
        // refresh members list after update
    }
    #[On('deleted')]
    public function showDeletedToast() {
        // refresh members list after delete
        Flux::toast(
            heading: __('Account deleted'),
            text: __('The account has been deleted successfully.'),
            variant: 'success',
        );
    }

    public function mount(Event $event, Church $church, $members) {
        $this->event = $event;
        $this->church = $church;
        $this->members = $members;
    }

    public function setRole($string) {
        if(in_array($string, ['ambassador', 'pastor'])) {
            return __('Follow-Up Admin');
        } else if ($string === 'church_member') {
            return __('Church Member');
        } else {
            return Str::headline($string);
        }
    }
    
  
    public function updated() {
        $this->dispatch('invitation_sent');
        Flux::toast(
            heading: __('Changes saved'),
            text: __('Your changes have been saved successfully.'),
            variant: 'success',
        );
    }

    public function render()
    {
        return view('livewire.members.index');
    }
}
