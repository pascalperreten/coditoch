<div>
    <form wire:submit.prevent="addLanguage" class="space-y-6">
        <flux:heading>{{ __('Languages') }}</flux:heading>
        <div class="flex">
            <flux:field class="w-full">
                <flux:input.group class="outline-blue-200"
                    description="{{ __('Specify all languages that should be selectable in the form') }}">
                    <flux:input autofocus wire:model="form.name"
                        class="!ring-offset-0 !focus:ring-offset-0 !outline-offset-0 !focus:outline-offset-0"
                        placeholder="{{ __('Language') }}" />
                    <flux:button type="submit" icon="plus">
                        {{ __('Add') }}</flux:button>
                </flux:input.group>
                <flux:error name="form.name" />
            </flux:field>

        </div>
    </form>
    <div class="mt-6">
        @if (isset($this->form->languages))
            @foreach ($this->form->languages as $language)
                <livewire:languages.item wire:key="language-{{ $language->id }}" :event="$this->event" :language_id="$language->id" />
            @endforeach
        @endif
    </div>
</div>
