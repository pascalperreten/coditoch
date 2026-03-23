<div class="flex gap-4 items-center justify-between">
        @if ($district = $this->district())
        <flux:input wire:keydown.enter="updateDistrict({{ $district->id }})" wire:model.blur="form.name"
            :disabled="!array_key_exists('district', $this->form->edit) || $this->form->edit['district'] !== $district->id">

            <x-slot name="iconTrailing">
                @if (!array_key_exists('district', $this->form->edit) || $this->form->edit['district'] !== $district->id)
                    <flux:tooltip content="{{ __('Edit') }}">
                        <flux:button wire:click="editItem({{ $district->id }})" size="sm" variant="subtle"
                            icon="pencil-square" class="-mr-1" />
                    </flux:tooltip>
                @else
                    <flux:button wire:click="updateDistrict({{ $district->id }})" size="sm" variant="subtle"
                        icon="check" class="-mr-1" variant="primary" color="green" />
                @endif
            </x-slot>
        </flux:input>
        <flux:modal.trigger :name="'delete-district-' . $district->id">
            <flux:tooltip content="{{ __('Delete') }}">
                <flux:button icon="x-mark" variant="primary" color="red" class="cursor-pointer" />
            </flux:tooltip>
        </flux:modal.trigger>

        <flux:modal :name="'delete-district-' . $district->id" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Delete District') }}?</flux:heading>
                    <flux:text class="mt-2">{{ __('You are about to delete this district.') }}<br>
                        {{ __('This action cannot be undone.') }}

                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="deleteDistrict({{ $district->id }})" variant="danger">
                        {{ __('Delete District') }}</flux:button>
                </div>
            </div>
        </flux:modal>
    @endif
</div>
