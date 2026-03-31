<div class="flex flex-col gap-6">

    <form wire:submit="save" class="flex flex-col gap-6">

        <!-- First Name -->
        <flux:field>
            <flux:label>{{ __('First Name') }}</flux:label>
            <flux:input readonly placeholder="{{ __('First Name') }}" autocomplete="given-name" autofocus wire:model="first_name"
                type="text" />
            <flux:error name="first_name" />
        </flux:field>


        <!-- Last Name -->
        <flux:field>
            <flux:label>{{ __('Last Name') }}</flux:label>
            <flux:input readonly placeholder="{{ __('Last Name') }}" autocomplete="family-name" wire:model="last_name"
                type="text" />
            <flux:error name="last_name" />
        </flux:field>

        <!-- Email Address -->
        <flux:field>
            <flux:label>{{ __('Email address') }}</flux:label>
            <flux:input readonly placeholder="email@example.com" autocomplete="email" wire:model="email"
                type="email" />
            <flux:error name="email" />
        </flux:field>

        <!-- Password -->
        <flux:field>
            <flux:label>{{ __('Password') }}</flux:label>
            <flux:input placeholder="{{ __('Password') }}" autocomplete="new-password" wire:model="password" type="password"
                viewable />
            <flux:error name="password" />
        </flux:field>

        <!-- Confirm Password -->
        <flux:field>
            <flux:label>{{ __('Confirm password') }}</flux:label>
            <flux:input placeholder="{{ __('Confirm password') }}" autocomplete="new-password" wire:model="password_confirmation"
                type="password" viewable />
            <flux:error name="password_confirmation" />
        </flux:field>

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Create account') }}
            </flux:button>
        </div>
    </form>
</div>
