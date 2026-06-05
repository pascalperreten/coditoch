<div>
    <div class="space-y-4">

        <x-partials.header heading="{{ __('Manage') }}" />
        <div>
            <flux:breadcrumbs>
                @can('view', $this->ministry)
                    <flux:breadcrumbs.item href="{{ route('ministry', $this->ministry) }}" wire:navigate>
                        {{ $this->ministry->name }}
                    </flux:breadcrumbs.item>
                    <flux:breadcrumbs.item href="{{ route('events.show', [$this->ministry, $this->event]) }}" wire:navigate>
                        {{ $this->event->name }} - {{ $this->event->city }}
                    </flux:breadcrumbs.item>
                    <flux:breadcrumbs.item
                        href="{{ route('churches.show', [$this->ministry, $this->event, $this->church]) }}" wire:navigate>
                        {{ $this->church->name }}
                    </flux:breadcrumbs.item>
                @endcan
            </flux:breadcrumbs>

        </div>
        <flux:separator />

    </div>
    <div class="mt-6">
        <livewire:church-nav :ministry="$this->ministry" :event="$this->event" :church="$this->church">
            <flux:card>
                <flux:tab.group class="">
                    <flux:tabs scrollable scrollable:scrollbar="hide" wire:model="activeTab">
                        <flux:tab name="details">Details</flux:tab>
                        <flux:tab name="follow-up-contact">{{ __('Follow-Up Contact') }}</flux:tab>
                        @if ($this->evangelize)
                            <flux:tab name="evangelize">{{ __('Evangelize') }}</flux:tab>
                        @endif

                    </flux:tabs>
                    <flux:tab.panel name="details">
                        @if ($this->church->street === '')
                            <flux:text class="text-red-500 mb-4">
                                {{ __('Please fill out this form with all the required information!') }}</flux:text>
                        @endif
                        <livewire:churches.edit :ministry="$this->ministry" :church="$this->church" :event="$this->event" />
                    </flux:tab.panel>
                    <flux:tab.panel name="follow-up-contact">
                        <flux:select variant="listbox" wire:model.change="follow_up_contact"
                            label="{{ __('Follow-up Contact') }}" placeholder="{{ __('Follow-up Contact') }}"
                            description="{{ __('Please indicate the person we should contact for follow-up work.') }}">
                            @foreach ($this->church->members as $member)
                                <flux:select.option value="{{ $member->id }}" wire:key="{{ $member->id }}">
                                    {{ $member->first_name }} {{ $member->last_name }}
                                </flux:select.option>
                            @endforeach
                            @if (!$this->church->members)
                                <flux:select.option disabled>{{ __('No members have been added yet.') }}
                                </flux:select.option>
                            @endif
                        </flux:select>

                    </flux:tab.panel>

                    @if ($this->evangelize)
                        <flux:tab.panel name="evangelize">
                            <div class="space-y-8">
                                <div class="flex justify-between items-center">
                                    <flux:heading size="lg">{{ __('Evangelize') }}</flux:heading>
                                </div>
                                <flux:separator />
                                <flux:text>
                                    {{ __('Would you like to evangelize yourself and add people directly to your church?') }}
                                </flux:text>
                                <div class="space-y-2">
                                    <flux:text variant="strong">
                                        {{ __('Copy this link and send it to your evangelists') }}
                                    </flux:text>
                                    <flux:input readonly copyable value="{{ $this->addContact() }}"></flux:input>
                                </div>
                                <div class="space-y-2">
                                    <flux:text variant="strong">
                                        {{ __('Or open the form directly by clicking on this button.') }}
                                    </flux:text>
                                    <flux:button target="_blank" href="{{ $this->addContact() }}">
                                        {{ __('Add contacts') }}
                                    </flux:button>
                                </div>
                            </div>

                        </flux:tab.panel>
                    @endif
                </flux:tab.group>
            </flux:card>
        </livewire:church-nav>

    </div>
    <flux:toast />
</div>
