<div>
    <form wire:submit.prevent="addPostalCode" class="space-y-6">
        <flux:heading>{{ __('Postal Codes') }}</flux:heading>
        <div class="flex">
            <flux:field class="w-full">
                <flux:input.group class="outline-blue-200"
                    description="{{ __('Enter all postal codes in your catchment area') }}">
                    <flux:input autofocus wire:model="form.name"
                        class="!ring-offset-0 !focus:ring-offset-0 !outline-offset-0 !focus:outline-offset-0"
                        placeholder="{{ __('Postal Code') }}" />
                    <flux:button type="submit" icon="plus">
                        {{ __('Add') }}</flux:button>
                </flux:input.group>
                <flux:error name="form.name" />
            </flux:field>

        </div>
    </form>
    <div class="mt-6">
        @if (isset($this->form->postal_codes))
            @foreach ($this->form->postal_codes as $postal_code)
                <livewire:postal-codes.item :event="$this->event" postal_code_id="{{ $postal_code->id }}"
                    wire:key="postal_code-{{$postal_code->id }}" />
                <flux:separator variant="subtle" class="my-3" />
            @endforeach
        @endif
    </div>
</div>
