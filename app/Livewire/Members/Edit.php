<?php

namespace App\Livewire\Members;

use Livewire\Component;
use App\Models\User;
use App\Models\Church;
use App\Models\Ministry;
use App\Models\Event;
use App\Livewire\Forms\MemberForm;
use App\Notifications\Invitation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Flux\Flux;

class Edit extends Component
{
    use AuthorizesRequests;

    public bool $churchInvitation = false;
    public ?User $member = null;
    public Ministry $ministry;
    public $events;
    public MemberForm $form;
    public ?Church $church = null;

    public function mount(Church $church = null) {
        $this->church = $church;
        $this->form->church = $this->church;
        $this->form->setMember($this->member);
        $this->events = Event::where('ministry_id', $this->ministry->id)->get();
    }

    public function update() {
        $this->authorize('update', $this->member);
        $this->form->update($this->church);
        $this->dispatch('updated');
        $this->modal('edit-member-' . $this->member->id)->close();
        Flux::toast(
            heading: __('Account updated'),
            text: __('The account has been updated successfully.'),
            variant: 'success',
        );
        if($this->form->role === 'church_member') {
            $this->redirect(route('churches.contacts', [$this->ministry, $this->member->church->event, $this->member->church]), navigate: true);
        }
    }

    public function sendInvitation() {
        $this->member->notify(new Invitation($this->member, $this->ministry, $this->church->event)->locale(app()->getLocale()));
        $this->dispatch('updated');
        $this->modal('edit-member-' . $this->member->id)->close();
    }


    public function delete() {
        Flux::modals()->close();
        
        $this->form->delete();

        $this->dispatch('deleted');

        if($this->church->id) { 
            
        $this->redirect(route('churches.members', [$this->ministry, $this->church->event, $this->church]), navigate: true);
        } else {
            $this->redirect(route('ministry.members', [$this->ministry]), navigate: true);
        }
    }



    public function render()
    {
        return view('livewire.members.edit');
    }
}
