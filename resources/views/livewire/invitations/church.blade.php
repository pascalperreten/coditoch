<div class="max-w-md mx-auto mt-10">
    <form method="POST" action="{{ route('register.storeUserAndChurch', ['ministry' => $this->ministry, 'event' => $this->event, 'token' => $this->token]) }}" class="space-y-6">
        @csrf
        <div>
            <flux:heading size="lg">{{ __('Register Church') }}</flux:heading>
            <flux:text class="mt-2">{{ __('Please provide the following information') }}</flux:text>
        </div>
        <input type="hidden" name="event_id" value="{{ $this->event->id }}">
        <flux:field>
            <flux:label>{{ __('First Name') }}</flux:label>
            <flux:input name="first_name" type="text" :value="old('first_name')"/>
            <flux:error name="first_name" />
        </flux:field>
        <flux:field>
            <flux:label>{{ __('Last Name') }}</flux:label>
            <flux:input name="last_name" type="text" :value="old('last_name')"/>
            <flux:error name="last_name" />
        </flux:field>
        <flux:field>
            <flux:label>{{ __('Email') }}</flux:label>
            <flux:input name="email" type="email" :value="old('email')"/>
            <flux:error name="email" />
        </flux:field>
        <flux:field>
            <flux:label>{{ __('Phone') }}</flux:label>
            <flux:input name="phone" type="text" :value="old('phone')"/>
            <flux:error name="phone" />
        </flux:field>
        <flux:field>
            <flux:label>{{ __('Name Church') }}</flux:label>
            <flux:input name="church_name" type="text" :value="old('church_name')"/>
            <flux:error name="church_name" />
        </flux:field>
        <flux:field>
            <flux:label>{{ __('Role') }}</flux:label>
            <flux:select name="role" variant="listbox" placeholder="{{ __('Select Role') }}" value="{{ $this->role }}">
                <flux:select.option value="pastor">{{ __('Pastor') }}</flux:select.option>
                <flux:select.option value="ambassador">{{ __('Ambassador') }}</flux:select.option>
            </flux:select>
            <flux:error name="role" />
        </flux:field>
        <!-- Password -->
        <flux:field>
            <flux:label>{{ __('Password') }}</flux:label>
            <flux:input placeholder="{{ __('Password') }}" autocomplete="new-password" name="password" type="password"
                viewable :value="old('password')" />
            <flux:error name="password" />
        </flux:field>

        <!-- Confirm Password -->
        <flux:field>
            <flux:label>{{ __('Confirm password') }}</flux:label>
            <flux:input name="password_confirmation" placeholder="{{ __('Confirm password') }}" autocomplete="new-password" type="password" viewable />
            <flux:error name="password_confirmation" />
        </flux:field>

        <div class="flex">
            <flux:spacer />
            <flux:button type="submit" variant="primary">{{ __('Register Church') }}
            </flux:button>
        </div>
    </form>
</div>
