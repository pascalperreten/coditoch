<div class="flex gap-4 items-center justify-between">
    @if ($postal_code = $this->postal_code())
        <flux:input wire:keydown.enter="updatePostalCode({{ $postal_code->id }})" wire:model="form.name"
            :disabled="!array_key_exists('postal_code', $this->form->edit) || $this->form->edit['postal_code'] !== $postal_code->id">

            <x-slot name="iconTrailing">
                @if (!array_key_exists('postal_code', $this->form->edit) || $this->form->edit['postal_code'] !== $postal_code->id)
                    <flux:tooltip content="{{ __('Edit') }}">
                        <flux:button wire:click="editItem({{ $postal_code->id }})" size="sm" variant="subtle"
                            icon="pencil-square" class="-mr-1" />
                    </flux:tooltip>
                @else
                    <flux:button wire:click="updatePostalCode({{ $postal_code->id }})" size="sm" variant="subtle"
                        icon="check" class="-mr-1" variant="primary" color="green" />
                @endif
            </x-slot>
        </flux:input>
        <flux:modal.trigger :name="'delete-postal_code-' . $postal_code->id">
            <flux:tooltip content="{{ __('Delete') }}">
                <flux:button icon="x-mark" variant="primary" color="red" class="cursor-pointer" />
            </flux:tooltip>
        </flux:modal.trigger>

        <flux:modal :name="'delete-postal_code-' . $postal_code->id" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">{{ __('Delete Postal Code') }}?</flux:heading>
                    <flux:text class="mt-2">
                        {{ __('You are about to delete this postal code.') }}<br>
                        {{ __('This action cannot be undone.') }}
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="deletePostalCode({{ $postal_code->id }})" variant="danger">
                        {{ __('Delete Postal Code') }}</flux:button>
                </div>
            </div>
        </flux:modal>
    @endif
</div>
