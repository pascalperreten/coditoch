<?php

use Livewire\Component;
use App\Models\Ministry;
use App\Models\Event;
use App\Models\Church;

new class extends Component {
    public Ministry $ministry;
    public Event $event;
    public Church $church;

    public $badgeNumber = '';

    public function mount(Ministry $ministry, Event $event, Church $church)
    {
        $this->ministry = $ministry;
        $this->event = $event;
        $this->church = $church;

           
        if(auth()->user()->role === 'church_member') {
            $this->badgeNumber = auth()->user()->contacts->count();
        } else {
            $this->badgeNumber = $this->church->contacts()->count();
        }
    }

    public function getCount() {
            return $this->church->contacts->count();
        }


};
?>

<div class="space-y-6">
    <div class="space-y-4">
        <x-partials.header heading="{{ __('Contacts') }}" badgeText="{{ $this->badgeNumber }}"
            color="amber" />

        @can('view', $this->event)
            <div>
                <flux:breadcrumbs>
                    @can('view', $this->ministry)
                        <flux:breadcrumbs.item href="{{ route('ministry', $this->ministry) }}" wire:navigate>
                            {{ $this->ministry->name }}
                        </flux:breadcrumbs.item>
                    @endcan
                    <flux:breadcrumbs.item href="{{ route('events.show', [$this->ministry, $this->event]) }}" wire:navigate>
                        {{ $event->name }} - {{ $event->city }}</flux:breadcrumbs.item>
                    @can('update', $this->church)
                        <flux:breadcrumbs.item
                            href="{{ route('churches.show', [$this->ministry, $this->event, $this->church]) }}" wire:navigate>
                            {{ $this->church->name }}</flux:breadcrumbs.item>
                    @endcan
                </flux:breadcrumbs>
            </div>
            <flux:separator />
        @endcan
    </div>
    <livewire:church-nav :ministry="$this->ministry" :event="$this->event" :church="$this->church">
        <div class="space-y-6">
            <livewire:contacts-table :church="$this->church" :event="$this->event" variant="church" />
        </div>
    </livewire:church-nav>

</div>
