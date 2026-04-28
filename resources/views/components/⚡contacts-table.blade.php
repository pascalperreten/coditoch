<?php

use Livewire\Component;
use App\Models\Contact;
use App\Models\Church;
use App\Models\Event;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Carbon\Carbon;

new class extends Component {
    use WithPagination;

    public $invalidContacts;
    public $variant;
    public $showMore = [];
    protected string $paginationTheme = 'tailwind';

    //public Contact $contact;
    public ?Church $church;
    public ?Event $event;

    public function mount()
    {
        //
    }

    #[On('updated')]
    public function refresh()
    {
        //
    }

    public function districtMapUrl($contact)
    {
        if ($contact->postalCode->first() && $contact->district()->first()) {
            $address = urlencode($contact->postalCode->first()->name . ', ' . $contact->city . ' ' . $contact->district->first()->name);
        } elseif ($contact->postalCode->first() && !$contact->district()->first()) {
            $address = $contact->postalCode->first()->name . ', ' . $contact->city;
        } elseif (!$contact->postalCode->first() && $contact->district()->first()) {
            $address = $contact->district->first()->name . ', ' . $contact->city;
        }

        return "https://www.google.com/maps/search/?api=1&query={$address}";
    }

    public function cityUrl($contact)
    {
        $city = $contact->city;
        return "https://www.google.com/maps/search/?api=1&query={$city}";
    }
    public function plzUrl($contact)
    {
        $plz = $contact->postalCode->first()->name;
        $city = $contact->city;
        $query = urlencode($plz . ', ' . $city);
        return "https://www.google.com/maps/search/?api=1&query={$query}";
    }
    public function districtUrl($contact)
    {
        $district = $contact->district->first()->name;
        $city = $contact->city;
        $query = urlencode($district . ', ' . $city);
        return "https://www.google.com/maps/search/?api=1&query={$query}";
    }

    public function shortText($text, $id)
    {
        if (Str::length($text) > 30) {
            $this->showMore[] = $id;
            return Str::limit($text, 30);
        } else {
            return $text;
        }
    }

    public function setDate($date)
    {
        return Carbon::parse($date)->format('d.m.Y');
    }

    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function refreshContacts() {
        $this->contacts();
    }

    #[Computed]
    public function contacts()
    {
        if ($this->variant === 'event') {
            return Contact::query()->where('event_id', $this->event->id)->tap(fn($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)->paginate(20);
        } elseif ($this->variant === 'church') {
            if (auth()->user()->role === 'church_member') {
                return Contact::query()->where('church_id', $this->church->id)->whereHas('followUpPerson', fn($query) => $query->where('id', auth()->user()->id))->tap(fn($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)->paginate(20);
            } else {
                return Contact::query()->where('church_id', $this->church->id)->where('assigned', true)->tap(fn($query) => $this->sortBy ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)->paginate(20);
            }
        }
    }
};
?>

<div>
    <flux:card>
        <flux:table>
            <flux:table.columns>
                <flux:table.column sortable :sorted="$sortBy === 'created_at'" :direction="$sortDirection"
                    wire:click="sort('created_at')">{{ __('Date') }}</flux:table.column>
                <flux:table.column>{{ __('Name') }}</flux:table.column>
                @if (!isset($this->church))
                    <flux:table.column sortable :sorted="$sortBy === 'church_id'" :direction="$sortDirection"
                        wire:click="sort('church_id')">
                        {{ __('Church') }}</flux:table.column>
                @endif
                <flux:table.column>{{ __('Infos') }}</flux:table.column>
                @if (auth()->user()->role !== 'church_member')
                    <flux:table.column>{{ __('Follow-Up Person') }}</flux:table.column>
                @endif
                <flux:table.column sortable :sorted="$sortBy === 'contacted_date'" :direction="$sortDirection"
                    wire:click="sort('contacted_date')">{{ __('Contacted') }}</flux:table.column>
                <flux:table.column sortable :sorted="$sortBy === 'met'" :direction="$sortDirection"
                    wire:click="sort('met')">{{ __('Meeting') }}</flux:table.column>
                <flux:table.column>{{ __('Church') }}</flux:table.column>
                <flux:table.column></flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows wire:poll.10s="refreshContacts">

                @if ($this->contacts()->count() <= 0)
                <flux:table.row wire:key="no-contacts">
                    <flux:table.cell colspan="100%">
                        <flux:text class="text-center italic">
                            {{ __('No contacts have been added yet.') }}
                        </flux:text>
                    </flux:table.cell>
                </flux:table.row>
                @endif
                
                @foreach ($this->contacts() as $contact)
                    <flux:table.row wire:key="contact-{{ $contact->id }}">
                        <flux:table.cell>{{ $this->setDate($contact->created_at) }}</flux:table.cell>
                        <flux:table.cell>{{ $contact->name }}</flux:table.cell>
                        @if (!isset($this->church))
                            <flux:table.cell>
                                @if ($contact->church_id)
                                    {{ $contact->church->name }}
                                @else
                                    {{ $contact->church_name ?? __('No church assigned') }}
                                @endif
                            </flux:table.cell>
                        @endif
                        <flux:table.cell>
                            <flux:modal.trigger name="contact-{{ $contact->id }}-info">
                                <flux:button icon="information-circle" />
                            </flux:modal.trigger>

                            <flux:modal name="contact-{{ $contact->id }}-info">
                                <div class="space-y-2 max-w-md">
                                    <flux:heading size="xl">{{ $contact->name }}</flux:heading>
                                    <flux:separator />
                                    @if (!$contact->decision)
                                        <flux:text class="text-red-400 text-center rounded-sm font-bold p-2 text-sm">
                                            {{ __('This person has not made a decision for Christ yet!') }}
                                        </flux:text>
                                    @endif
                                    <flux:heading size="lg">{{ __('Gender') }}</flux:heading>
                                    <flux:text>
                                        {{ $contact->gender === 'male' ? __('Male') : __('Female') }}
                                    </flux:text>
                                    <flux:separator />
                                    <flux:heading size="lg">{{ __('Address') }}</flux:heading>
                                    <flux:text>
                                        {{ __('City') . ': ' }}
                                        <flux:link target="_blank" href="{{ $this->cityUrl($contact) }}">
                                            {{ $contact->city }}
                                        </flux:link>
                                    </flux:text>
                                    @if ($contact->postalCode()->exists())
                                        <flux:text>

                                            {{ __('Postal Code') . ': ' }}

                                            <flux:link target="_blank" href="{{ $this->plzUrl($contact) }}">
                                                {{ $contact->postalCode->first()->name }}
                                            </flux:link>

                                        </flux:text>
                                    @endif
                                    @if ($contact->district()->exists())
                                        <flux:text>
                                            {{ __('District') . ': ' }}

                                            <flux:link target="_blank" href="{{ $this->districtUrl($contact) }}">
                                                {{ $contact->district->first()->name }}
                                            </flux:link>

                                        </flux:text>
                                    @endif
                                    <flux:separator />
                                    <flux:heading size="lg">{{ __('Contact Information') }}
                                    </flux:heading>
                                    <div x-data="{ copied: false }" class="">
                                        <div class="space-y-2">
                                            @if ($contact->way_to_get_in_contact === 'phone')
                                                <flux:heading>{{ __('Phone') }}</flux:heading>
                                            @elseif ($contact->way_to_get_in_contact === 'social_media')
                                                <flux:heading>
                                                    {{ ucfirst($contact->social_media['platform']) }}
                                                </flux:heading>
                                            @elseif($contact->way_to_get_in_contact === 'email')
                                                <flux:heading>{{ __('Email') }}</flux:heading>
                                            @elseif ($contact->way_to_get_in_contact === 'other_contact')
                                                <flux:heading>{{ __('Other') }}</flux:heading>
                                            @endif
                                            <flux:separator />
                                            <div class="flex justify-between gap-2">
                                                @if ($contact->way_to_get_in_contact === 'phone')
                                                    <flux:text>{{ $contact->phone }}
                                                    </flux:text>
                                                    <span class="flex gap-2">
                                                        <flux:link href="tel:{{ $contact->phone }}">
                                                            <flux:icon.phone />
                                                        </flux:link>
                                                        <flux:separator vertical />
                                                        <flux:icon.clipboard class="cursor-pointer justify-self-end"
                                                            x-show="!copied"
                                                            x-on:click="$clipboard('{{ $contact->phone }}');
                                                            copied = true;
                                                            setTimeout(() => copied = false, 2000)" />
                                                        <flux:icon.clipboard-document-check x-show="copied" />
                                                    </span>
                                                @elseif ($contact->way_to_get_in_contact === 'social_media')
                                                    <flux:text>
                                                        {{ ucfirst($contact->social_media['user_name']) }}
                                                    </flux:text>
                                                    @if ($contact->social_media['url'])
                                                        <span class="flex gap-4">
                                                            <flux:link target="_blank"
                                                                href="{{ $contact->social_media['url'] }}">
                                                                <flux:icon.globe-alt />
                                                            </flux:link>
                                                            <flux:separator vertical />
                                                            <flux:icon.clipboard class="cursor-pointer justify-self-end"
                                                                x-show="!copied"
                                                                x-on:click="$clipboard('{{ $contact->social_media['url'] }}');
                                                            copied = true;
                                                            setTimeout(() => copied = false, 2000)" />
                                                            <flux:icon.clipboard-document-check x-show="copied" />
                                                        </span>
                                                    @endif
                                                @elseif($contact->way_to_get_in_contact === 'email')
                                                    <flux:text>{{ $contact->email }}
                                                    </flux:text>
                                                    <span class="flex gap-4">
                                                        <flux:link href="mailto:{{ $contact->email }}">
                                                            <flux:icon.envelope />
                                                        </flux:link>
                                                        <flux:separator vertical />
                                                        <flux:icon.clipboard class="cursor-pointer justify-self-end"
                                                            x-show="!copied"
                                                            x-on:click="$clipboard('{{ $contact->email }}');
                                                            copied = true;
                                                            setTimeout(() => copied = false, 2000)" />
                                                        <flux:icon.clipboard-document-check x-show="copied" />
                                                    </span>
                                                @elseif ($contact->way_to_get_in_contact === 'other_contact')
                                                    <flux:text>{{ $contact->other_contact }}
                                                    </flux:text>
                                                @endif
                                            </div>
                                        </div>

                                    </div>

                                    @if ($contact->evangelist_name)
                                        <flux:separator />
                                        <div>
                                            <flux:heading size="lg">{{ __('Evangelist Name') }}
                                            </flux:heading>
                                            <flux:text>{{ $contact->evangelist_name }}</flux:text>
                                        </div>
                                    @endif
                                    @if ($contact->comments)
                                        <flux:separator />
                                        <div>
                                            <flux:heading size="lg">{{ __('Comments') }}
                                            </flux:heading>
                                            <flux:text>{{ $contact->comments }}</flux:text>
                                        </div>
                                    @endif

                                </div>
                            </flux:modal>
                        </flux:table.cell>

                        @if (auth()->user()->role !== 'church_member')
                            <flux:table.cell>
                                @if ($contact->followUpPerson)
                                    <livewire:contact-card :contact="$contact->followUpPerson" />
                                @else
                                    <flux:text>{{ __('Not assigned yet') }}</flux:text>
                                @endif
                            </flux:table.cell>
                        @endif
                        <flux:table.cell>

                            @if ($contact->invalid_contact_details)
                                <flux:badge color="red">{{ __('invalid') }}</flux:badge>
                            @elseif ($contact->contacted_date)
                                <flux:badge color="green">
                                    {{ $this->setDate($contact->contacted_date) }}
                                </flux:badge>
                            @else
                                <flux:badge color="red">{{ __('pending') }}</flux:badge>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                            @if (!$contact->invalid_contact_details)
                                @if ($contact->not_interested)
                                    <flux:badge color="red">{{ __('no interest') }}</flux:badge>
                                @elseif($contact->meeting_date)
                                    <flux:badge color="{{ $contact->met ? 'green' : 'orange' }}">
                                        {{ $this->setDate($contact->meeting_date) }}
                                    </flux:badge>
                                @else
                                    <flux:badge color="red">{{ __('pending') }}</flux:badge>
                                @endif
                            @endif

                        </flux:table.cell>
                        <flux:table.cell>
                            @if (!$contact->invalid_contact_details)
                                @if ($contact->part_of_church)
                                    <flux:badge color="green">{{ __('part of church') }}</flux:badge>
                                @else
                                    <flux:badge color="red">{{ __('pending') }}</flux:badge>
                                @endif
                            @endif

                        </flux:table.cell>
                        @if (isset($this->church))
                            <flux:table.cell class="text-end" inset="top-bottom">
                                <livewire:contacts.edit-dates wire:key="contact-{{ $contact->id }}"
                                    :contact="$contact" />
                            </flux:table.cell>
                        @endif
                        @can ('update', $contact)
                            <flux:table.cell class="text-end" inset="top-bottom">
                                <flux:button icon="pencil-square" wire:key="edit-{{ $contact->id }}" href="{{ route('contacts.edit', [$this->event->ministry, $this->event, $contact]) }}" wire:navigate />
                            </flux:table.cell>
                        @endcan

                    </flux:table.row>
                @endforeach
                @if (auth()->user()->role === 'church_member' && auth()->user()->contacts->count() === 0)
                    <flux:table.row wire:key="no-contacts-message">
                        <flux:table.cell colspan="100%">
                            <flux:text class="text-center italic">
                                {{ __('No contacts have been assigned to you yet.') }}
                            </flux:text>
                        </flux:table.cell>
                    </flux:table.row>
                @endif

            </flux:table.rows>

        </flux:table>
        <!-- $orders = Order::paginate() -->
        <flux:pagination :paginator="$this->contacts()" />
    </flux:card>
</div>
