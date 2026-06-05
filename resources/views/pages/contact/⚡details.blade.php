<?php

use Livewire\Component;
use App\Models\Ministry;
use App\Models\Event;
use App\Models\Contact;

new class extends Component {
    public Ministry $ministry;
    public Event $event;
    public $contactsWithoutChurch = 0;

    public function mount()
    {
        $this->contactsWithoutChurch = $this->event->contacts()->whereNull('evangelist_church_id')->count();
    }

    public function hasGospelShares($church)
    {
        return Contact::where('evangelist_church_id', $church->id)->count();
    }
};
?>

<div class="space-y-6">
    <div class="space-y-4">

        <x-partials.header heading="{{ __('Statistics') }}" />

        <flux:breadcrumbs>
            @can('view', $this->ministry)
                <flux:breadcrumbs.item href="{{ route('ministry', $this->ministry) }}" wire:navigate>
                    {{ $this->ministry->name }}
                </flux:breadcrumbs.item>
            @endcan
            <flux:breadcrumbs.item href="{{ route('events.show', [$this->ministry, $this->event]) }}" wire:navigate>
                {{ $event->name }} - {{ $event->city }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        <flux:separator />

    </div>
    <livewire:event-nav :ministry="$this->ministry" :event="$this->event">
        <div class="space-y-6">
            <flux:button icon="arrow-left" href="{{ route('events.stats', [$this->ministry, $this->event]) }}"
                wire:navigate>
                {{ __('Statistics') }}</flux:button>
            <flux:card>
                <div class="flex gap-4 items-center">
                    <flux:heading size="lg">{{ __('Total Number of Contacts') }}</flux:heading>
                    <flux:badge color="orange" size="lg">{{ $this->event->contacts->count() }}</flux:badge>
                </div>
            </flux:card>
            <flux:card>
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>{{ __('Who evangelized?') }}</flux:table.column>
                        <flux:table.column align="end">{{ __('Number of Contacts') }}</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        <flux:table.row>
                            <flux:table.cell>{{ $this->event->name }} - {{ $this->event->city }}</flux:table.cell>
                            <flux:table.cell align="end">
                                {{ $this->event->contacts()->whereNull('evangelist_church_id')->count() }}
                            </flux:table.cell>
                        </flux:table.row>
                        @foreach ($this->event->churches as $church)
                            @if ($this->hasGospelShares($church) > 0)
                                <flux:table.row :key="$church->id">
                                    <flux:table.cell>{{ $church->name }}</flux:table.cell>
                                    <flux:table.cell align="end">
                                        {{ $this->hasGospelShares($church) }}
                                    </flux:table.cell>
                                </flux:table.row>
                            @endif
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            </flux:card>
        </div>


    </livewire:event-nav>
</div>
