<?php

use Livewire\Component;
use App\Models\Ministry;
use App\Models\Event;
use App\Models\Contact;

new class extends Component {
    public Ministry $ministry;
    public Event $event;
    public $decisions;
    public $gospel_shares;
    public $total_gospel_shares;
    public $contacts_decided;
    public $decisions_without_contact_details;
    public $contacts_not_decided;
    public $contacted;
    public $met;
    public $part_of_church;
    public $not_interested;
    public $invalid_contact_details;
    public array $decisions_data = [];
    public array $contacts_data = [];

    public function mount(Ministry $ministry, Event $event)
    {
        $this->ministry = $ministry;
        $this->event = $event;
        $this->decisions_without_contact_details = $this->event->decisions->sum('number_of_decisions');
        $this->contacts = $this->event->contacts->count();
        $this->contacts_decided = $this->event->contacts->where('decision', true)->count();
        $this->decisions = $this->decisions_without_contact_details + $this->contacts_decided;
        $this->gospel_shares = $this->event->gospelShares->sum('number_of_gospel_shares');
        $this->contacts_not_decided = $this->event->contacts->where('decision', false)->count();
        $this->contacted = $this->event->contacts->whereNotNull('contacted_date')->where('invalid_contact_details', false)->count();
        $this->met = $this->event->contacts->where('met', true)->where('invalid_contact_details', false)->count();
        $this->part_of_church = $this->ministry->contacts->where('part_of_church', true)->where('invalid_contact_details', false)->count();
        $this->not_interested = $this->ministry->contacts->where('not_interested', true)->where('invalid_contact_details', false)->count();
        $this->invalid_contact_details = $this->ministry->contacts->where('invalid_contact_details', true)->count();



        $this->decisions_data = [['name' => __('Total'), 'value' => $this->decisions], ['name' => __('With contact details'), 'value' => $this->contacts_decided], ['name' => __('Without contact details'), 'value' => $this->decisions_without_contact_details]];

        $this->contacts_data = [['name' => __('Total'), 'value' => $this->contacts], ['name' => __('Decision'), 'value' => $this->contacts_decided], ['name' => __('No decision'), 'value' => $this->contacts_not_decided], ['name' => __('Contacted'), 'value' => $this->contacted], ['name' => __('Met'), 'value' => $this->met], ['name' => __('Part of Church'), 'value' => $this->part_of_church], ['name' => __('Not Interested'), 'value' => $this->not_interested], ['name' => __('Invalid Details'), 'value' => $this->invalid_contact_details]];
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
            <flux:card>
                <div class="flex justify-between gap-4 items-center">
                    <div class="flex gap-4 items-center">
                        <flux:heading size="lg">{{ __('Gospel Shares') }}</flux:heading>
                        <flux:badge color="orange" size="lg">{{ $this->gospel_shares }}</flux:badge>
                    </div>
                    <flux:button href="{{ route('events.gospel-shares', [$this->ministry, $this->event]) }}"
                        wire:navigate>
                        {{ __('Entries') }}</flux:button>
                </div>
            </flux:card>
            <flux:card>
                <div class="flex justify-between gap-4 items-center">
                    <div class="flex gap-4 items-center">
                        <flux:heading size="lg">{{ __('Decisions') }}</flux:heading>
                        <flux:badge color="orange" size="lg">{{ $this->decisions }}</flux:badge>

                    </div>
                    <flux:button href="{{ route('events.decisions', [$this->ministry, $this->event]) }}"
                            wire:navigate>
                            {{ __('Entries') }}</flux:button>
                </div>
                <flux:chart wire:model="decisions_data" class="h-80 mt-6">
                    <flux:chart.svg>
                        <flux:chart.bar field="value" class="text-blue-300 dark:text-blue-700" width="30%" />

                        <flux:chart.axis axis="x" field="name">
                            <flux:chart.axis.tick />
                        </flux:chart.axis>

                        <flux:chart.axis axis="y">
                            <flux:chart.axis.grid />
                            <flux:chart.axis.tick />
                        </flux:chart.axis>
                    </flux:chart.svg>

                    <flux:chart.tooltip>
                        <flux:chart.tooltip.value field="value" label="{{ __('Number') }}" />
                    </flux:chart.tooltip>
                </flux:chart>
            </flux:card>
            <flux:separator />
            <flux:card>
                <div class="flex gap-4 justify-between items-center">
                    <div class="flex gap-4 items-center">
                        <flux:heading size="lg">{{ __('Contacts') }}</flux:heading>
                        <flux:badge color="orange" size="lg">{{ $this->contacts }}</flux:badge>
                    </div>
                    <flux:button href="{{ route('events.contacts.details', [$this->ministry, $this->event]) }}"
                        wire:navigate>
                        {{ __('Details') }}</flux:button>
                </div>
                <flux:chart wire:model="contacts_data" class="h-80 mt-6">
                    <flux:chart.svg>
                        <flux:chart.bar field="value" class="text-blue-300 dark:text-blue-700" width="80%" />

                        <flux:chart.axis axis="x" field="name">
                            <flux:chart.axis.tick />
                        </flux:chart.axis>

                        <flux:chart.axis axis="y">
                            <flux:chart.axis.grid />
                            <flux:chart.axis.tick />
                        </flux:chart.axis>
                    </flux:chart.svg>

                    <flux:chart.tooltip>
                        <flux:chart.tooltip.value field="value" label="{{ __('Number') }}" />
                    </flux:chart.tooltip>
                </flux:chart>
            </flux:card>
        </div>
    </livewire:event-nav>

</div>
