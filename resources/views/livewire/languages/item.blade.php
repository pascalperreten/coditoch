<div>
    @if ($language = $this->language())
        <div class="flex gap-4 items-center justify-between">
            <flux:input wire:keydown.enter="updateLanguage({{ $language->id }})" wire:model="form.name"
                :disabled="!array_key_exists('language', $this->form->edit) || $this->form->edit['language'] !== $language->id">

                <x-slot name="iconTrailing">
                    @if (!array_key_exists('language', $this->form->edit) || $this->form->edit['language'] !== $language->id)
                        <flux:tooltip content="{{ __('Edit') }}">
                            <flux:button wire:click="editItem({{ $language->id }})" size="sm" variant="subtle"
                                icon="pencil-square" class="-mr-1" />
                        </flux:tooltip>
                    @else
                        <flux:button wire:click="updateLanguage({{ $language->id }})" size="sm" variant="subtle"
                            icon="check" class="-mr-1" variant="primary" color="green" />
                    @endif
                </x-slot>
            </flux:input>
            <flux:modal.trigger :name="'delete-language-' . $language->id">
                <flux:tooltip content="{{ __('Delete') }}">
                    <flux:button icon="x-mark" variant="primary" color="red" class="cursor-pointer" />
                </flux:tooltip>
            </flux:modal.trigger>

            <flux:modal :name="'delete-language-' . $language->id" class="min-w-[22rem]">
                <div class="space-y-6">
                    <div>
                        <flux:heading size="lg">{{ __('Delete Language') }}?</flux:heading>
                        <flux:text class="mt-2">
                            {{ __('You are about to delete this language.') }}<br>
                            {{ __('This action cannot be undone.') }}
                        </flux:text>
                    </div>
                    <div class="flex gap-2">
                        <flux:spacer />
                        <flux:modal.close>
                            <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                        </flux:modal.close>
                        <flux:button wire:click="deleteLanguage({{ $language->id }})" variant="danger">
                            {{ __('Delete Language') }}</flux:button>
                    </div>
                </div>
            </flux:modal>
        </div>

        <flux:error name="form.name" class="mt-1" />
        <flux:separator variant="subtle" class="my-3" />
    @endif
</div>
