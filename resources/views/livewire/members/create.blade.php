<div class="w-full">
    <form wire:submit.prevent="sendInvitation" class="space-y-6">
        <div>
            <flux:heading size="lg">{{ $title }}</flux:heading>
            <flux:text class="mt-2">{{ __('Please provide the following information') }}</flux:text>
        </div>
        @if ($this->churchInvitation)
            <flux:field>
                <flux:label>{{ __('Name Church') }}</flux:label>
                <flux:input required wire:model="form.church_name" type="text" />
                <flux:error name="form.church_name" />
            </flux:field>
        @endif
        <flux:field>
            <flux:label>{{ __('First Name') }}</flux:label>
            <flux:input required wire:model="form.first_name" type="text" />
            <flux:error name="form.first_name" />
        </flux:field>
        <flux:field>
            <flux:label>{{ __('Last Name') }}</flux:label>
            <flux:input required wire:model="form.last_name" type="text" />
            <flux:error name="form.last_name" />
        </flux:field>
        <flux:field>
            <flux:label>{{ __('Email') }}</flux:label>
            <flux:input required wire:model="form.email" type="email" />
            <flux:error name="form.email" />
        </flux:field>
        <flux:field>
            <flux:label>{{ __('Phone') }}</flux:label>
            <flux:input wire:model="form.phone" type="text" />
            <flux:error name="form.phone" />
        </flux:field>
        <flux:radio.group wire:model="form.gender" label="{{ __('Gender') }}">
            <flux:radio value="male" label="{{ __('Male') }}" />
            <flux:radio value="female" label="{{ __('Female') }}" />
        </flux:radio.group>
        <flux:field>
            <flux:label>{{ __('Role') }}</flux:label>
            <flux:select required wire:model.live="form.role" variant="listbox"
                placeholder="{{ __('Select the role') }}">

                @if ($this->church?->id || $this->churchInvitation)
                    <flux:select.option value="ambassador">{{ __('Follow Up Admin') }}</flux:select.option>
                    <flux:select.option value="church_member">{{ __('Member') }}</flux:select.option>
                @else
                    <flux:select.option value="admin">Admin</flux:select.option>
                    <flux:select.option value="editor">Editor</flux:select.option>
                    <flux:select.option value="follow_up">Follow Up</flux:select.option>
                @endif
            </flux:select>
            <flux:error name="form.role" />
        </flux:field>
        @if ($this->form->role === 'follow_up')
            <flux:field>
                <flux:label>Event</flux:label>
                <flux:description>{{ __('On which events should this person do the follow up?') }}</flux:description>
                <flux:select required multiple wire:model.live="form.events" variant="listbox"
                    placeholder="{{ __('Select the events') }}">
                    @foreach ($this->events as $event)
                        <flux:select.option value="{{ $event->id }}">{{ $event->name }} - {{ $event->city }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="form.events" />
            </flux:field>
        @endif

        <div class="flex">
            <flux:spacer />
            <flux:button type="submit" variant="primary">{{ __('Invite') }}
            </flux:button>
        </div>
    </form>
</div>
