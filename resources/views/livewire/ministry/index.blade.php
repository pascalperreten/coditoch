<div class="space-y-6">
    <div class="space-y-4">
        <x-partials.header heading="{{ $this->ministry->name }}" />
    </div>
    <livewire:ministry-nav :ministry="$this->ministry">
        <div>
            @if (count($this->ministry->events) >= 1)
                <div class="sm:p-6 sm:rounded-xl sm:border space-y-6">
                    <flux:heading size="lg">{{ __('Overview') }} {{ $ministry->name }}</flux:heading>
                    <div class="flex justify-between items-center">
                        <flux:heading>{{ __('Gospel Shares') }}</flux:heading>
                        <flux:badge color="orange" size="lg">{{ $this->gospel_shares }}</flux:badge>
                    </div>
                    <div class="flex justify-between items-center">
                        <flux:heading>{{ __('Decisions for Christ') }}</flux:heading>
                        <flux:badge color="orange" size="lg">{{ $this->total_decisions }}</flux:badge>
                    </div>
                    <div class="flex justify-between items-center">
                        <flux:heading>{{ __('Contacts') }}</flux:heading>
                        <flux:badge color="orange" size="lg">{{ count($this->ministry->contacts) }}</flux:badge>
                    </div>
                    <flux:separator />
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($this->ministry->events as $event)
                            <div wire:key="{{ $event->id }}">
                                <flux:card wire:click="showEvent({{ $event }})"
                                    class="space-y-4 cursor-pointer">

                                    <div class="flex justify-between items-center">
                                        <flux:heading>{{ $event->name }} {{ __($event->city) }}</flux:heading>
                                    </div>
                                    <flux:separator />
                                    <div class="flex justify-between items-center">
                                        <flux:text>{{ __('Decisions') }}</flux:text>
                                        <flux:badge color="orange">{{ $this->getDecisions($event) }}</flux:badge>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <flux:text>{{ __('Contacts') }}</flux:text>
                                        <flux:badge color="orange">{{ count($event->contacts) }}</flux:badge>
                                    </div>
                                    @if ($this->newContacts($event))
                                        <flux:text class="text-red-400 text-center rounded-sm font-bold p-2 text-sm">
                                            {{ __('You have new Contacts!') }}</flux:text>
                                    @endif

                                </flux:card>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                @can('create', App\Models\User::class)
                    <flux:card class="space-y-6 text">
                        <flux:heading class="text-center" size="lg">{{ __('You have no events yet!') }}</flux:heading>
                        <flux:text class="text-center">{{ __('Create your first event now and start collecting contacts.') }}</flux:text>
                        <div class="flex justify-center">
                            <flux:button wire:navigate
                                href="{{ route('events.index', [$this->ministry, 'q' => 'create']) }}" wire:navigate>
                                {{ __('Create Event') }}
                            </flux:button>
                        </div>
                    </flux:card>
                @endcan

            @endif
        </div>
    </livewire:ministry-nav>
</div>

</div>
