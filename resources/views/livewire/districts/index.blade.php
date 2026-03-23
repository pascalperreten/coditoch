<div>
    <form wire:submit.prevent="addDistrict" class="space-y-6">
        <flux:heading>{{ __('Districts') }}</flux:heading>
        <div class="flex">
            <flux:field class="w-full">
                <flux:input.group class="outline-blue-200"
                    description="{{ __('Specify all districts that should be selectable in the form') }}">
                    <flux:input wire:model="form.name"
                        class="!ring-offset-0 !focus:ring-offset-0 !outline-offset-0 !focus:outline-offset-0"
                        placeholder="{{ __('District') }}" />
                    <flux:button type="submit" icon="plus">
                        {{ __('Add') }}</flux:button>
                </flux:input.group>
                <flux:error name="form.name" />
            </flux:field>

        </div>
    </form>
    <div class="mt-6">
        @if (isset($this->form->districts))
            @foreach ($this->form->districts as $district)
                <livewire:districts.item :event="$this->event" district_id="{{ $district->id }}" wire:key="district-{{$district->id}}" />
                <flux:separator variant="subtle" class="my-3" />
            @endforeach
        @endif
    </div>
</div>
