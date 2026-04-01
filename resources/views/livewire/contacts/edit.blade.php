<div class="space-y-6">
    <div class="space-y-4">
        <x-partials.header heading="{{ __('Contacts') }}" badgeText="{{ $event->contacts->count() }}" color="amber" />
        <div>
            <flux:breadcrumbs>
                @can('view', $this->ministry)
                    <flux:breadcrumbs.item href="{{ route('ministry', $this->ministry) }}" wire:navigate>
                        {{ $this->ministry->name }}
                    </flux:breadcrumbs.item>
                @endcan

                <flux:breadcrumbs.item href="{{ route('events.show', [$this->ministry, $this->event]) }}" wire:navigate>
                    {{ $this->event->name }} - {{ $this->event->city }}
                </flux:breadcrumbs.item>

                <flux:breadcrumbs.item href="{{ route('contacts.index', [$this->ministry, $this->event]) }}" wire:navigate>
                    {{ __('Contacts') }}
                </flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>
        <flux:separator />
    </div>

    <livewire:event-nav :ministry="$this->ministry" :event="$this->event">
        <flux:card>
            <div class="space-y-4">
                <flux:heading size="lg">{{ $contact->name }}</flux:heading>
                <flux:text><span class="font-bold">{{ __('Place of residence') }}: </span>{{ $contact->city }}</flux:text>
                @if ($contact->foreign_city)
                    <flux:modal.trigger name="lives-in-this-city">
                        <flux:button variant="outline">{{ __('Lives in') }} {{ $this->event->city }}</flux:button>
                    </flux:modal.trigger>
                @else
                    <flux:modal.trigger name="lives-in-another-city">
                        <flux:button variant="outline">{{ __('Lives in another City') }}</flux:button>
                    </flux:modal.trigger>
                @endif
                @if (!$contact->foreign_city)
                    <div class="mt-4 space-y-4">
                        <flux:label>{{ __('Church') }}</flux:label>
                        @if ($contact->church)
                            <flux:text>{{ $contact->church->name }}</flux:text>
                        @else
                            <flux:text>{{ __('No church assigned') }}</flux:text>
                            
                        @endif
                        <flux:modal.trigger name="change-church">
                            <flux:button variant="outline">{{ __('Change Church') }}</flux:button>
                        </flux:modal.trigger>
                    </div>
                @else
                    <div class="max-w-sm mt-4 space-y-4">
                        <form wire:submit.prevent="updateChurch" class="space-y-4">
                            <flux:field>
                                <flux:label>{{ __('Church') }}</flux:label>
                                <flux:input type="text" wire:model.live="church_name" />
                                <flux:error name="church_name" />
                            </flux:field>
                            <flux:button :disabled="$church_name === $contact->church_name" type="submit" variant="primary">{{ __('Update Church') }}</flux:button>
                        </form>
                    </div>
                @endif
                <div class="flex">
                    <flux:spacer />
                    <flux:modal.trigger name="delete-contact">
                        <flux:button variant="danger">{{ __('Delete Contact') }}</flux:button>
                    </flux:modal.trigger>
                </div>
                
            </div>
        </flux:card>
        <flux:modal name="lives-in-this-city">
            <div class="space-y-4">
                <flux:heading>{{ __('Contact lives in this city') }}</flux:heading>
                    <flux:text>{{ __('This contact is marked as living in a different city than the event. If this is not correct, you can change it by clicking the button below.') }}
                </flux:text>
                <div class="flex">
                    <flux:spacer />
                    <flux:button wire:click="livesInThisCity" variant="primary">
                        {{ __('Mark as living in this city') }}</flux:button>
                </div>
            </div>
        </flux:modal>
        <flux:modal name="lives-in-another-city">
            <div class="space-y-4">
                <flux:heading>{{ __('Contact lives in another city') }}</flux:heading>
                    <flux:text>{{ __('This contact is marked as living in the same city as the event. If this is not correct, you can change it by clicking the button below.') }}
                </flux:text>
                <form wire:submit.prevent="livesInAnotherCity" class="space-y-4">
                    <flux:field>
                        <flux:label>{{ __('City') }}</flux:label>
                        <flux:input wire:model="newCity" type="text" />
                        <flux:error name="newCity" />
                    </flux:field>
                    <div class="flex">
                        <flux:spacer />
                        <flux:button wire:click="livesInAnotherCity" variant="primary">
                            {{ __('Mark as living in another city') }}</flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>
        <flux:modal name="delete-contact">
            <div class="space-y-4">
                <flux:heading>{{ __('Delete Contact') }}</flux:heading>
                    <flux:text>{{ __('Are you sure you want to delete this contact?') }} {{ __('This action cannot be undone.') }}</flux:text>

                <div class="flex">
                    <flux:spacer />
                    <flux:button wire:click="delete" variant="danger">
                        {{ __('Delete Contact') }}</flux:button>
                </div>
            </div>
        </flux:modal>
        <flux:modal name="change-church">
            <div class="space-y-4">
                <flux:heading>{{ __('Change Church') }}</flux:heading>
                    <flux:text>{{ __('Are you sure you want to change the church for this contact?') }} {{ __('This action cannot be undone.') }}</flux:text>

                <div class="flex">
                    <flux:spacer />
                    <flux:button wire:click="changeChurch" variant="danger">
                        {{ __('Change Church') }}</flux:button>
                </div>
            </div>
        </flux:modal>
    </livewire:event-nav>
    <flux:toast />
</div>

