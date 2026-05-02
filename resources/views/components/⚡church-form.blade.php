<?php

use Livewire\Component;
use App\Models\Ministry;
use App\Models\Event;
use App\Models\Church;
use App\Models\District;
use App\Models\Language;
use App\Models\PostalCode;
use App\Livewire\Forms\ChurchForm;
use App\Livewire\Forms\DistrictForm;
use App\Livewire\Forms\LanguageForm;
use App\Livewire\Forms\PostalCodeForm;

new class extends Component {
    public Ministry $ministry;
    public Event $event;
    public ?Church $church;
    public churchForm $form;
    public DistrictForm $district_form;
    public LanguageForm $language_form;
    public PostalCodeForm $postal_code_form;

    public function mount(Event $event, ?Church $church)
    {
        $this->event = $event;
        $this->church = $church;
        $this->district_form->setDistricts($this->event);
        $this->language_form->setLanguages($this->event);
        $this->postal_code_form->setPostalCodes($this->event);
        if ($this->church) {
            $this->form->setChurch($this->church);
        }
    }

    public function createDistrict()
    {
        $this->district_form->event = $this->event;
        $newDistrict = $this->district_form->create();
        $this->modal('create-district')->close();
        $this->district_form->setDistricts($this->event);
        $this->form->districts[] = $newDistrict->id;
    }
    public function createLanguage()
    {
        $this->language_form->event = $this->event;
        $newLanguage = $this->language_form->addLanguage();
        $this->modal('create-language')->close();
        $this->language_form->setLanguages($this->event);
        $this->form->languages[] = $newLanguage->id;
    }

    public function createPostalCode()
    {
        $this->postal_code_form->event = $this->event;
        $newPostalCode = $this->postal_code_form->addPostalCode();
        $this->modal('create-postal-code')->close();
        $this->postal_code_form->setPostalCodes($this->event);
        $this->form->postal_codes[] = $newPostalCode->id;
    }

    public function save()
    {
        if ($this->church?->id) {
            $this->form->update($this->church);
            session()->flash('success', __('Church updated succesfully'));
            $this->redirectRoute('churches.show', [$this->ministry, $this->event, $this->church], navigate: true);
        } else {
            $this->form->create($this->event);
            return $this->redirect(route('churches.index', [$this->ministry, $this->event]), navigate: true);
        }
    }

    public function delete()
    {
        foreach ($this->church->contacts as $contact) {
            $contact->update([
                'assigned' => false,
            ]);
        }
        $this->church->delete();

        session()->flash('success', 'Church deleted!');
        return $this->redirectRoute('churches.index', [$this->ministry, $this->event], navigate: true);
    }
};
?>

<div>
    <form wire:submit.prevent="save" class="space-y-6">

        <flux:field>
            <flux:label>{{ __('Name Church') }}</flux:label>

            <flux:input wire:model="form.name" type="text" />

            <flux:error name="form.name" />
        </flux:field>
        <flux:field>
            <flux:label>{{ __('Street') }}</flux:label>

            <flux:input wire:model="form.street" type="text" />

            <flux:error name="form.street" />
        </flux:field>
        <flux:field>
            <flux:label>{{ __('Postal Code') }}</flux:label>

            <flux:input wire:model="form.postal_code" type="text" />

            <flux:error name="form.postal_code" />
        </flux:field>

        <flux:field>
            <flux:label>{{ __('City') }}</flux:label>

            <flux:input wire:model="form.city" type="text" />

            <flux:error name="form.city" />
        </flux:field>

        <flux:pillbox searchable wire:key="district" wire:model.live="form.districts" label="{{ __('Districts') }}"
            placeholder="{{ __('Districts') }}"
            description="{{ __('Please list all districts in which your church is active.') }}" multiple>
            <flux:pillbox.option.create modal="create-district">{{ __('Add') }}</flux:pillbox.option>

                @foreach ($this->district_form->districts as $district)
                    <flux:pillbox.option value="{{ $district->id }}" wire:key="{{ $district->id }}">
                        {{ $district->name }}
                    </flux:pillbox.option>
                @endforeach

        </flux:pillbox>

        <flux:pillbox wire:key="language" searchable wire:model.live="form.languages" label="{{ __('Languages') }}"
            placeholder="{{ __('Languages') }}"
            description="{{ __('Please list all languages that you can support in the follow-up process.') }}"
            multiple>
            <flux:pillbox.option.create modal="create-language">{{ __('Add') }}</flux:pillbox.option>
                @foreach ($this->language_form->languages as $language)
                    <flux:pillbox.option value="{{ $language->id }}" wire:key="{{ $language->id }}">
                        {{ $language->translation->name }}
                    </flux:pillbox.option>
                @endforeach
        </flux:pillbox>

        <flux:pillbox searchable wire:key="postal_code" wire:model.live="form.postal_codes"
            label="{{ __('Postal Codes') }}" placeholder="{{ __('Postal Codes') }}"
            description="{{ __('Please list all postal codes in which your church is active.') }}" multiple>
            <flux:pillbox.option.create modal="create-postal-code">{{ __('Add') }}</flux:pillbox.option>
                @foreach ($this->postal_code_form->postal_codes as $postal_code)
                    <flux:pillbox.option value="{{ $postal_code->id }}" wire:key="{{ $postal_code->id }}">
                        {{ $postal_code->name }}
                    </flux:pillbox.option>
                @endforeach

        </flux:pillbox>

        <flux:field>
            <flux:label>{{ __('Description') }}</flux:label>
            <flux:description>{{ __('Please briefly describe your church (distinctive features, things that are good to know)') }}</flux:description>

            <flux:textarea wire:model="form.description" />

            <flux:error name="form.description" />
        </flux:field>

        <flux:field>
            <flux:label>{{ __('Website Url') }}</flux:label>

            <flux:input wire:model="form.website_url" type="url" />

            <flux:error name="form.website_url" />
        </flux:field>
        <div class="flex justify-end gap-2">
            @if ($this->church?->id)
                <flux:button variant="primary" type="button" wire:click="save">{{ __('Save Changes') }}</flux:button>
                @can('delete', $church)
                    <flux:modal.trigger name="delete-church-{{ $church->id }}">
                        <flux:button variant="danger" class="text-red-500">{{ __('Delete') }}</flux:button>
                    </flux:modal.trigger>

                    <flux:modal name="delete-church-{{ $church->id }}" class="md:w-96">
                        <div class="space-y-6 text-left">
                            <div>
                                <flux:heading size="lg">{{ $church->name }} {{ __('delete') }}</flux:heading>
                                <flux:text class="mt-2 text-red-500">
                                    {{ __('Are you sure you want to delete this church?') }}
                                    <br>{{ __('This action cannot be undone.') }}
                                </flux:text>
                            </div>

                            <div class="flex">
                                <flux:spacer />

                                <flux:button type="button" wire:click="delete()" variant="danger">
                                    {{ __('Delete Church') }}
                                </flux:button>
                            </div>
                        </div>
                    </flux:modal>
                @endcan
            @else
                @can('create', \App\Models\Church::class)
                    <flux:button variant="primary" type="submit">{{ __('Create Church') }}
                    </flux:button>
                @endcan
            @endif
        </div>
    </form>

    <flux:modal name="create-district" class="md:w-96 space-y-6">
        <form wire:submit.prevent="createDistrict" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Create new district') }}</flux:heading>
                <flux:text class="mt-2">{{ __('Enter the name of the new district.') }}</flux:text>
            </div>
            <flux:input autofocus wire:model="district_form.name" label="Name" placeholder="{{ __('District') }}" />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">{{ __('Create District') }}</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="create-language" class="md:w-96 space-y-6">
        <form wire:submit.prevent="createLanguage" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Create new language') }}</flux:heading>
                <flux:text class="mt-2">{{ __('Enter the name of the new language.') }}</flux:text>
            </div>
            <flux:input autofocus wire:model="language_form.name" label="Name" placeholder="{{ __('Language') }}" />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">{{ __('Create') }}</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="create-postal-code" class="md:w-96 space-y-6">
        <form wire:submit.prevent="createPostalCode" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Create new postal code') }}</flux:heading>
                <flux:text class="mt-2">{{ __('Enter the name of the new postal code.') }}</flux:text>
            </div>
            <flux:input autofocus wire:model="postal_code_form.name" label="Name"
                placeholder="{{ __('Postal Code') }}" />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">{{ __('Create') }}</flux:button>
            </div>
        </form>
    </flux:modal>

</div>
