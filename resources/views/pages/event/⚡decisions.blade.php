<?php

use Livewire\Component;
use App\Models\Ministry;
use App\Models\Event;
use App\Models\Church;
use App\Models\Decision;
use App\Models\Contact;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;
    public Ministry $ministry;
    public Event $event;
    public $tab = 'all';
    public $numberDecisions = 0;
    public $contacts = 0;
    public $totalDecisons = 0;

    public function mount(Ministry $ministry, Event $event)
    {
        $this->ministry = $ministry;
        $this->event = $event;
        $this->numberDecisions = Decision::where('event_id', $this->event->id)->pluck('number_of_decisions')->sum();
        $this->contacts = Contact::where('event_id', $this->event->id)->where('decision', true)->count();
        $this->totalDecisons = $this->numberDecisions + $this->contacts;
    }

    public function date($date)
    {
        return \Carbon\Carbon::parse($date)->format('d.m.Y | H:i');
    }

    #[\Livewire\Attributes\Computed]
    public function decisions()
    {
        return Decision::where('event_id', $this->event->id)->latest()->paginate(100);
    }

    public function decisionsEvent()
    {
        $query = Decision::where('event_id', $this->event->id)
            ->whereNull('church_id');

        return [
            'sum' => $query->sum('number_of_decisions'),
            'count' => $query->count(),
        ];
    }

    public function decisionsChurches() {
        return Decision::where('event_id', $this->event->id)
            ->whereNotNull('church_id')
            ->with('church')
            ->get()
            ->groupBy('church_id')
            ->map(function ($decisions, $church_id) {
                return [
                    'church' => Church::find($church_id),
                    'number_of_decisions' => $decisions->sum('number_of_decisions'),
                    'decisions_count' => $decisions->count(),
                ];
            });
    }
};
?>

<div class="space-y-6">
    <div class="space-y-4">

        <x-partials.header heading="{{ __('Statistics') }}" />
        @can('update', $this->event)
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="{{ route('ministry', $this->ministry) }}" wire:navigate>
                    {{ $this->ministry->name }}
                </flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="{{ route('events.show', [$this->ministry, $this->event]) }}" wire:navigate>
                    {{ $this->event->name }}
                </flux:breadcrumbs.item>
            </flux:breadcrumbs>
            <flux:separator />
        @endcan

    </div>
    <livewire:event-nav :ministry="$this->ministry" :event="$this->event">
        <div class="space-y-6">
            <flux:button icon="arrow-left" href="{{ route('events.stats', [$this->ministry, $this->event]) }}"
                wire:navigate>
                {{ __('Statistics') }}</flux:button>
            <flux:card>
                <flux:tab.group>
                    <flux:tabs wire:model="tab">
                        <flux:tab name="all">{{ __('All') }}</flux:tab>
                        <flux:tab name="churches">{{ __('Churches') }}</flux:tab>
                    </flux:tabs>

                    <flux:tab.panel name="all">
                        <flux:table :paginate="$this->decisions()">
                            <flux:table.columns>

                                <flux:table.column>{{ __('Date') }}</flux:table.column>
                                <flux:table.column>{{ __('Name Evangelist') }}</flux:table.column>
                                <flux:table.column align="end">{{ __('Number') }}</flux:table.column>
                            </flux:table.columns>

                            <flux:table.rows>
                                <flux:table.row wire:key="with-contadt-details">
                                    <flux:table.cell></flux:table.cell>
                                    <flux:table.cell>
                                        {{ __('With contact details') }}
                                    </flux:table.cell>
                                    <flux:table.cell align="end">
                                        {{ $this->contacts }}
                                    </flux:table.cell>
                                </flux:table.row>
                                @foreach ($this->decisions() as $decision)
                                    <flux:table.row wire:key="decision-{{ $decision->id }}">
                                        <flux:table.cell>{{ $this->date($decision->created_at) }}</flux:table.cell>
                                        <flux:table.cell>
                                            {{ $decision->evangelist_name }}
                                        </flux:table.cell>
                                        <flux:table.cell align="end">
                                            {{ $decision->number_of_decisions }}
                                        </flux:table.cell>
                                    </flux:table.row>
                                @endforeach
                            </flux:table.rows>
                        </flux:table>
                    </flux:tab.panel>
                    <flux:tab.panel name="churches">
                        <flux:table>
                            <flux:table.columns>
                                <flux:table.column>{{ __('Name Church') }}</flux:table.column>
                                <flux:table.column>{{ __('Number of Entries') }}</flux:table.column>
                                <flux:table.column align="end">{{ __('Number of Decisions') }}</flux:table.column>
                            </flux:table.columns>

                            <flux:table.rows>
                                <flux:table.row wire:key="with-contadt-details">
                                    <flux:table.cell>
                                        {{ __('Decisions') . ' ' . $this->event->name }}
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        {{ $this->decisionsEvent()['count'] }}
                                    </flux:table.cell>
                                    <flux:table.cell align="end">
                                        {{ $this->decisionsEvent()['sum'] }}
                                    </flux:table.cell>
                                </flux:table.row>
                                @foreach ($this->decisionsChurches() as $decisionChurch)
                                
                                    <flux:table.row wire:key="church-{{ $decisionChurch['church']->id }}">
                                        <flux:table.cell>{{ $decisionChurch['church']->name }}</flux:table.cell>
                                        <flux:table.cell>
                                            {{ $decisionChurch['decisions_count'] }}
                                        </flux:table.cell>
                                        <flux:table.cell align="end">
                                            {{ $decisionChurch['number_of_decisions'] }}
                                        </flux:table.cell>
                                    </flux:table.row>
                                @endforeach
                            </flux:table.rows>
                        </flux:table>
                    </flux:tab.panel>
                </flux:tab.group>
                

            </flux:card>
        </div>
    </livewire:event-nav>
</div>
