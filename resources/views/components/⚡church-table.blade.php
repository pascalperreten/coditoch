<?php

use Livewire\Component;
use App\Models\Event;
use App\Models\Ministry;
use App\Models\Church;
use Flux\Flux;
use Livewire\Attributes\On;

new class extends Component {
    public $search = '';
    public Event $event;
    public Ministry $ministry;

    public function mapsUrl($church)
    {
        $address = urlencode($church->street . ', ' . $church->postal_code . ' ' . $church->city);
        return "https://www.google.com/maps/search/?api=1&query={$address}";
    }

    public function address($church)
    {
        $address = $church->street . ', ' . $church->postal_code . ' ' . $church->city;
        return $address;
    }

    public function churches()
    {
        return $this->event->churches()
             ->where('name', 'like', "%{$this->search}%")
             ->orderBy('name')
             ->get();
    }

    public function searchReset()
    {
        $this->search = '';
    }

    public function deleteChurch($churchId)
    {
        $church = $this->event->churches()->findOrFail($churchId);
        foreach ($church->contacts as $contact) {
            $contact->update([
                'assigned' => false,
            ]);
        }
        $church->delete();

        Flux::toast('Kirche gelöscht', 'Die Kirche wurde erfolgreich gelöscht.', 'success');
    }

    public function getContactCount($church)
    {
        return $church->contacts()->where('assigned', true)->count();
    }
};
?>

<div class="space-y-6">
    <div class="flex gap-2">
        <div class="w-60">
            <flux:input type="text" wire:model.live="search" placeholder="{{ __('Search churches...') }}" />
        </div>
        @if ($this->search !== '')
            <flux:button icon="x-mark" wire:click="searchReset" />
        @endif

    </div>
    <flux:table>
        <flux:table.columns sticky>
            <flux:table.column>Name</flux:table.column>
            <flux:table.column>{{ __('Address') }}</flux:table.column>
            <flux:table.column>{{ __('Follow-Up Contact') }}</flux:table.column>
            <flux:table.column>{{ __('Number of Follow Up people') }}</flux:table.column>
            <flux:table.column>{{ __('Number of Contacts') }}</flux:table.column>
            <flux:table.column>{{ __('Website') }}</flux:table.column>
            <flux:table.column></flux:table.column>

        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->churches() as $church)
                <flux:table.row wire:key="{{ $church->id }}">
                    <flux:table.cell>{{ $church->name }}
                    </flux:table.cell>
                    <flux:table.cell class="flex items-center gap-2">
                        <flux:dropdown>
                            <flux:button icon:trailing="map"></flux:button>
                            <flux:menu keep-open>
                                <div class="flex gap-2 items-center p-4">
                                    <a class="underline" target="_blank" href="{{ $this->mapsUrl($church) }}">
                                        <flux:text>
                                            {{ $church->street }}
                                        </flux:text>
                                        <flux:text>
                                            {{ $church->postal_code }} {{ $church->city }}
                                        </flux:text>
                                    </a>
                                    <flux:separator vertical />
                                    <div x-data="{ copied: false }">

                                        <flux:icon.clipboard class="cursor-pointer justify-self-end" x-show="!copied"
                                            x-on:click="$clipboard('{{ $this->address($church) }}');
                                                            copied = true;
                                                            setTimeout(() => copied = false, 2000)" />
                                        <flux:icon.clipboard-document-check x-show="copied" />
                                    </div>
                                </div>
                            </flux:menu>
                        </flux:dropdown>

                    </flux:table.cell>
                    <flux:table.cell>
                        @if ($church->followUpContact)
                            <div class="flex items-center gap-2">
                                <livewire:follow-up-contact align="start" :church="$church" wire:key="follow-up-contact-{{ $church->id }}" />
                            </div>
                        @else
                            <flux:text>{{ __('No Follow-Up Contact') }}</flux:text>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:tooltip toggleable>
                            <flux:button size="sm" icon="user">
                                {{ $church->members->count() }}
                            </flux:button>
                            <flux:tooltip.content class="max-w-[20rem] space-y-2 text-left">
                                <h6 class="text-white font-bold">{{ __('Number of Follow Up people') }}</h6>
                                <p>{{ __('Men') }}: {{ $church->members->where('gender', 'male')->count() }}</p>
                                <p>{{ __('Women') }}: {{ $church->members->where('gender', 'female')->count() }}</p>
                            </flux:tooltip.content>
                        </flux:tooltip>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:tooltip toggleable>
                            <flux:button size="sm" icon="user">
                                {{ $this->getContactCount($church) }}
                            </flux:button>
                            <flux:tooltip.content class="max-w-[20rem] space-y-2 text-left">
                                <h6 class="text-white font-bold">{{ __('Number of Contacts') }}</h6>
                                <p>{{ __('Men') }}: {{ $church->contacts->where('gender', 'male')->where('assigned', true)->count() }}</p>
                                <p>{{ __('Women') }}: {{ $church->contacts->where('gender', 'female')->where('assigned', true)->count() }}</p>
                            </flux:tooltip.content>
                        </flux:tooltip>
                    </flux:table.cell>
                    <flux:table.cell>
                        @if ($church->website_url)
                            <flux:dropdown>
                                <flux:button icon:trailing="globe-alt"></flux:button>
                                <flux:menu keep-open>
                                    <div class="flex gap-2 items-center p-4">
                                        <a class="underline" target="_blank" href="{{ $church->website_url }}">
                                            <flux:text>
                                                {{ $church->website_url }}
                                            </flux:text>
                                        </a>
                                        <flux:separator vertical />
                                        <div x-data="{ copied: false }">

                                            <flux:icon.clipboard class="cursor-pointer justify-self-end"
                                                x-show="!copied"
                                                x-on:click="$clipboard('{{ $church->website_url }}');
                                                            copied = true;
                                                            setTimeout(() => copied = false, 2000)" />
                                            <flux:icon.clipboard-document-check x-show="copied" />
                                        </div>
                                    </div>
                                </flux:menu>
                            </flux:dropdown>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>
                        <div class="flex justify-end">
                            <a href="{{ route('churches.show', [$ministry, $event, $church]) }}" wire:navigate>
                                <flux:icon.arrow-right-circle />
                            </a>
                        </div>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
