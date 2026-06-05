<div>
    <div class="space-y-4">

        <x-partials.header heading="{{ $this->church->name }}" />

        @can('view', $this->event)
            <div>
                <flux:breadcrumbs>
                    <flux:breadcrumbs.item href="{{ route('ministry', $this->ministry) }}" wire:navigate>
                        {{ $this->ministry->name }}
                    </flux:breadcrumbs.item>
                    <flux:breadcrumbs.item
                        href="{{ route('events.show', [$this->ministry, $this->event->city_slug, $this->event]) }}"
                        wire:navigate>
                        {{ $this->event->name }} - {{ $this->event->city }}
                    </flux:breadcrumbs.item>
                </flux:breadcrumbs>
            </div>
            <flux:separator />
        @endcan
    </div>

    <div class="mt-6">
        <livewire:church-nav :ministry="$this->ministry" :event="$this->event" :church="$this->church">
            <flux:card class="space-y-4">
                <flux:heading size="lg">{{ __('Information') }}</flux:heading>

                <flux:separator />
                <flux:heading>
                    {{ __('Address') }}
                </flux:heading>
                @if ($this->church->street !== '')
                    <flux:text><a target="_blank" class="underline"
                            href="{{ $this->mapsUrl($this->church) }}">{{ $this->church->street . ', ' . $this->church->postal_code . ' ' . $this->church->city }}</a>
                    </flux:text>
                @endif
                <flux:heading>{{ __('Languages') }}</flux:heading>
                @foreach ($this->church->languages as $language)
                    <flux:badge>
                        {{ $language->translation->name }}
                    </flux:badge>
                @endforeach
                <flux:heading>{{ __('Districts') }}</flux:heading>
                @foreach ($this->church->districts as $district)
                    <flux:badge>
                        {{ $district->name }}
                    </flux:badge>
                @endforeach

                <flux:separator />

                <div class="grid md:grid-cols-3 gap-4">
                    {{-- <div class="rounded-md shadow p-4 border space-y-4">
                        <flux:heading class="text-center">Pastor</flux:heading>
                        <flux:separator />
                        @foreach ($this->church->pastors as $pastor)
                            @if ($pastor)
                                <div class="flex justify-between items-center">
                                    <flux:text>{{ $pastor->first_name ?? __('No Pastor') }}
                                        {{ $pastor->last_name ?? '' }}
                                    </flux:text>
                                    <div>
                                        <livewire:contact-card align="end" :contact="$pastor" />
                                    </div>

                                </div>
                            @endif
                        @endforeach
                    </div> --}}
                    {{-- <div class="rounded-md shadow p-4 border space-y-4">
                        <flux:heading class="text-center">{{ __('Ambassador') }}</flux:heading>
                        <flux:separator />
                        @foreach ($this->church->ambassadors as $ambassador)
                            @if ($ambassador)
                                <div class="flex justify-between items-center">

                                    <flux:text>{{ $ambassador->first_name ?? __('No Ambassador') }}
                                        {{ $ambassador->last_name ?? '' }}
                                    </flux:text>
                                    <div>
                                        <livewire:contact-card align="end" :contact="$ambassador" />
                                    </div>

                                </div>
                            @endif
                        @endforeach
                    </div> --}}
                    <div class="rounded-md shadow p-4 border space-y-4">
                        <flux:heading class="text-center">{{ __('Follow-Up Contact') }}</flux:heading>
                        <flux:separator />
                        <div class="flex justify-between items-center">
                            <flux:text>
                                {{ $church->followUpContact->first_name ?? __('No Follow-Up Contact') }}
                                {{ $church->followUpContact->last_name ?? '' }}
                            </flux:text>
                            <div>
                                @if ($church->followUpContact)
                                    <livewire:follow-up-contact align="end" :church="$church" />
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </flux:card>
        </livewire:church-nav>
    </div>
    <flux:toast />
</div>
