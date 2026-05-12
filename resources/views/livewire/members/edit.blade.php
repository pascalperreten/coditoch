<div>
    <div class="p-2" wire:key="member-{{ $member->id }}">
        <flux:modal.trigger name="edit-member-{{ $member->id }}">
            <flux:icon.pencil-square class="cursor-pointer" />
        </flux:modal.trigger>

    
    </div>
    <flux:modal wire:key="edit-member-{{ $member->id }}" name="edit-member-{{ $member->id }}" class="w-md">

        <form wire:submit.prevent="update" class="space-y-6 text-left">
            <div class="space-y-4">
                <flux:heading size="lg">{{ app()->getLocale() === 'en' ? 'Edit' : '' }}
                    {{ $member->first_name . ' ' . $member->last_name }}
                    {{ app()->getLocale() === 'de' ? 'bearbeiten' : '' }}
                </flux:heading>
                @if ($member->ministry && $member->ministry->owner->id === $member->id)
                    <flux:text>{{ __('This member created the ministry and cannot be deleted.') }}</flux:text>
                    <flux:separator />
                @endif
                @if (!$member->password)
                    <div>
                        <flux:text class="mt-6" size="sm">
                            {{ __('This person has not yet responded to your invitation.') }}
                        </flux:text>
                        <flux:button class="mt-2" wire:click="sendInvitation">{{ __('Resend Invitation') }}
                        </flux:button>
                    </div>
                @endif
                <flux:text class="mt-2">{{ __('Please provide the following information') }}</flux:text>
            </div>
            <flux:field>
                <flux:label>{{ __('First Name') }}</flux:label>
                <flux:input wire:model="form.first_name" type="text" />
                <flux:error name="form.first_name" />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('Last Name') }}</flux:label>
                <flux:input wire:model="form.last_name" type="text" />
                <flux:error name="form.last_name" />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('Email') }}</flux:label>
                <flux:input wire:model="form.email" type="email" />
                <flux:error name="form.email" />
            </flux:field>
            <flux:field>
                <flux:label>{{ __('Phone') }}</flux:label>
                <flux:input wire:model="form.phone" type="text" />
                <flux:error name="form.phone" />
            </flux:field>
            <flux:radio.group wire:model="form.gender" label="{{ __('Gender') }}">
                <flux:radio value="male" label="{{ __('Male') }}" checked />
                <flux:radio value="female" label="{{ __('Female') }}" />
            </flux:radio.group>
            @if ($member->ministry && $member->ministry->user_id !== $member->id || !$member->ministry)
                <flux:field>
                    <flux:label>{{ __('Role') }}</flux:label>
                    <flux:select wire:model.live="form.role" variant="listbox" placeholder="Wähle die Funktion">

                        @if ($this->church->id !== null || $this->churchInvitation)
                            <flux:select.option value="pastor">Pastor</flux:select.option>
                            <flux:select.option value="ambassador">{{ __('Ambassador') }}</flux:select.option>
                            <flux:select.option value="church_member">{{ __('Member') }}</flux:select.option>
                        @else
                            <flux:select.option value="admin">Admin</flux:select.option>
                            <flux:select.option value="editor">Editor</flux:select.option>
                            <flux:select.option value="follow_up">Follow Up</flux:select.option>
                        @endif
                    </flux:select>
                    <flux:error name="form.role" />
                </flux:field>
                @if ($member->id === auth()->user()->id && auth()->user()->role !== 'church_member' && $this->form->role === 'church_member')
                    <flux:text class="text-red-500 text-sm">
                        {{ __('If you change your role to Member, you will not be able to change it again!') }}
                    </flux:text>
                @endif
            @endif
            @if ($this->form->role === 'follow_up')
                <flux:field>
                    <flux:label>Events</flux:label>
                    <flux:description>{{ __('On which events should this person do the follow up?') }}
                    </flux:description>
                    <flux:select multiple wire:model.live="form.events" variant="listbox"
                        placeholder="{{ __('Select the events') }}">
                        @foreach ($this->events as $event)
                            <flux:select.option value="{{ $event->id }}">{{ $event->name }} -
                                {{ $event->city }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="form.event_id" />
                </flux:field>
            @endif

            <div class="flex gap-2">
                <flux:spacer />
                <flux:button type="submit" variant="primary">{{ __('Save') }}</flux:button>
                @if ($member->ministry && $member->ministry->owner->id !== $member->id || !$member->ministry)
                    <flux:modal.trigger name="delete-member-{{ $member->id }}">
                        <flux:button type="button" variant="danger">{{ __('Delete') }}</flux:button>
                    </flux:modal.trigger>
                    
                @endif
            </div>
        </form>
    </flux:modal>
    <flux:modal wire:key="delete-member-{{ $member->id }}" name="delete-member-{{ $member->id }}" class="text-start min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">
                    {{ __('Would you like to delete this account?') }}
                </flux:heading>
                <flux:text class="mt-2">
                    {{ __('This action cannot be undone.') }}
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                </flux:modal.close>
                <flux:button type="button" wire:click="delete" variant="danger">{{ __('Delete') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
