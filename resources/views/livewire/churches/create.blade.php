<div class="space-y-6">
    <flux:tab.group>
        <flux:tabs scrollable scrollable:scrollbar="hide" wire:model="activeTab">
            <flux:tab name="form">Formular</flux:tab>
            <flux:tab name="email">E-Mail</flux:tab>
            <flux:tab name="link">Link</flux:tab>
        </flux:tabs>
        <flux:tab.panel name="form">
            <livewire:church-form :ministry="$this->ministry" :event="$this->event" />
        </flux:tab.panel>
        <flux:tab.panel name="email">
            <livewire:members.create :event="$this->event" :ministry="$this->ministry" :churchInvitation="true"
                title="{{ __('Invite Church') }}" />
        </flux:tab.panel>
        <flux:tab.panel name="link">
            <div class="space-y-6">
                @if (!$this->event->invitation_token)
                    <flux:button type="button" wire:click="newChurchLink" variant="primary">
                        {{ __('Create new invitation link') }}
                    </flux:button>
                @else
                    <flux:field variant="inline">
                        <flux:label>{{ __('Activate link') }}</flux:label>
                        <flux:description>{{ __('The link is disabled by default.') }}</flux:description>
                        <flux:switch wire:model.change="active_invitation_link" />
                        <flux:error name="active_invitation_link" />
                    </flux:field>

                    <flux:input copyable readonly type="text" value="{{ $this->addChurchLink() }}" />

                    <flux:modal.trigger name="new-link">
                        <flux:button>{{ __('Regenerate link') }}</flux:button>
                    </flux:modal.trigger>

                    <flux:modal name="new-link" class="md:w-96">
                        <div class="space-y-6">
                            <div>
                                <flux:heading size="lg">{{ __('Regenerate link') }}</flux:heading>
                                <flux:text class="text-red-500 mt-2">
                                    {{ __('If you regenerate this link, the previous one will no longer be valid.') }}
                                </flux:text>
                            </div>

                            <div class="flex">
                                <flux:spacer />

                                <flux:button type="button" wire:click="newChurchLink" variant="primary">
                                    {{ __('Regenerate link') }}</flux:button>
                            </div>
                        </div>
                    </flux:modal>
                @endif
            </div>
        </flux:tab.panel>
    </flux:tab.group>
    <flux:toast />
</div>
