<div>
    <flux:heading class="text-center" size="lg">
        {{ $this->event->name }} | {{ __($this->event->city) }}
        @if (isset($this->church))
            {{ ' | ' . $this->church->name }}
        @endif
    </flux:heading>
    <flux:separator class="my-6" />

    <div class="max-w-md m-auto">
        @if ($this->success_message !== '')
            <flux:card class="text-center space-y-6 p-10 m-auto max-w-md">
                <flux:heading size="xl">{{ $this->success_message }}</flux:heading>
                <flux:text>{{ __('Your entry has been successfully submitted!') }}</flux:text>
                <flux:button class="bg-cyan-700 hover:bg-cyan-800 cursor-pointer" variant="primary"
                    wire:click="newContact">{{ __('Back to the form') }}
                </flux:button>
            </flux:card>
        @else
            <div class="space-y-6">
                <flux:tab.group>
                    <flux:tabs variant="segmented" class="w-full" wire:model="with_contact">
                        <flux:tab wire:click="resetNumbers" name="with_contact">
                            {{ __('With contact details') }}
                        </flux:tab>
                        <flux:tab wire:click="resetContact" name="without_contact">
                            {{ __('Without contact details') }}
                        </flux:tab>
                    </flux:tabs>

                    <flux:tab.panel wire:key="with_contact_panel" class="space-y-6" name="with_contact">
                        @if (!$this->approved)
                            <div class="space-y-6">
                                <flux:heading>{{ __('Approval for sharing contact information') }}</flux:heading>
                                <flux:text>
                                    {{ __('The person agrees that their contact details may be passed on to a local church.') }}
                                </flux:text>

                                <flux:button wire:click="nextPage" class="bg-cyan-700 hover:bg-cyan-800 w-full"
                                    variant="primary">
                                    {{ __('Yes') }}
                                </flux:button>
                            </div>
                        @else
                        <form wire:submit.prevent="save" class="space-y-6">
                            <flux:field>
                                <flux:label>Name<span class="text-red-500">*</span></flux:label>
                                <flux:input wire:key="name" wire:model="form.name" type="text" />
                                <flux:error name="form.name" />
                            </flux:field>
                            <flux:field>
                                <flux:label>{{ __('How can we get in touch?') }}<span class="text-red-500">*</span>
                                </flux:label>
                                <flux:radio.group wire:key="way_to_get_in_contact"
                                    wire:model.change="form.way_to_get_in_contact" variant="buttons"
                                    class="w-full *:flex-1">
                                    <flux:radio value="phone">{{ __('Phone') }}</flux:radio>
                                    <flux:radio value="social_media">Social Media</flux:radio>
                                    <flux:radio value="email">{{ __('Email') }}</flux:radio>
                                    <flux:radio value="other_contact">{{ __('Other') }}</flux:radio>
                                </flux:radio.group>
                                <flux:error name="form.way_to_get_in_contact" />
                            </flux:field>
                            @if ($this->form->way_to_get_in_contact === '')
                                <flux:input wire:key="no_contact_method" disabled placeholder="{{ __('Please select the field above') }}">
                                </flux:input>
                            @endif

                            @if ($this->form->way_to_get_in_contact === 'phone')
                                <flux:field wire:key="phone_field">
                                    <flux:label>{{ __('Phone Number') }}<span class="text-red-500">*</span>
                                    </flux:label>
                                    <flux:input wire:key="phone" wire:model="form.phone" type="tel"
                                        pattern="\+?[0-9\- ]+" />
                                    <flux:error name="form.phone" />
                                </flux:field>
                            @elseif ($this->form->way_to_get_in_contact === 'social_media')
                                <div class="border border-gray-200 p-4 rounded space-y-6" wire:key="social_media_fields">
                                    <flux:field>
                                        <flux:label>{{ __('Which platform?') }}<span class="text-red-500">*</span>
                                        </flux:label>
                                        <flux:radio.group wire:key="social_platform"
                                            wire:model.live="form.social_platform" variant="buttons"
                                            class="w-full grid grid-cols-2 ">
                                            <flux:radio value="instagram">Instagram</flux:radio>
                                            <flux:radio value="facebook">Facebook</flux:radio>
                                            <flux:radio value="tiktok">Tik Tok</flux:radio>
                                            <flux:radio value="other_platform">{{ __('Other') }}</flux:radio>
                                        </flux:radio.group>
                                        <flux:error name="form.social_platform" />
                                    </flux:field>

                                    @if ($this->form->social_platform === 'other_platform')
                                        <flux:field wire:key="other_platform_field">
                                            <flux:label>{{ __('Platform') }}<span class="text-red-500">*</span>
                                            </flux:label>
                                            <flux:input wire:key="other_platform" wire:model="form.other_platform"
                                                type="text" />
                                            <flux:error name="form.other_platform" />
                                        </flux:field>
                                    @endif
                                    <flux:field wire:key="user_name_field">
                                        <flux:label>{{ __('Username') }}<span class="text-red-500">*</span>
                                        </flux:label>
                                        <flux:input wire:key="user_name" wire:model="form.user_name" type="text" />
                                        <flux:error name="form.user_name" />
                                    </flux:field>
                                    <flux:field wire:key="url_field">
                                        <flux:label>{{ __('URL to profile') }}</flux:label>
                                        <flux:input wire:key="url" wire:model="form.url" type="url" />
                                        <flux:error name="form.url" />
                                    </flux:field>
                                </div>
                            @elseif ($this->form->way_to_get_in_contact === 'email')
                                <flux:field wire:key="email_field">
                                    <flux:label>{{ __('Email Address') }}<span class="text-red-500">*</span>
                                    </flux:label>
                                    <flux:input wire:key="email" wire:model="form.email" type="email" />
                                    <flux:error name="form.email" />
                                </flux:field>
                            @elseif ($this->form->way_to_get_in_contact === 'other_contact')
                                <flux:field wire:key="other_contact_field">
                                    <flux:label>{{ __('Contact Information') }}<span class="text-red-500">*</span>
                                    </flux:label>
                                    <flux:input wire:key="other_contact" wire:model="form.other_contact"
                                        type="text" />
                                    <flux:error name="form.other_contact" />
                                </flux:field>
                            @endif
                            {{-- Foreign City / Postal Code and District --}}
                            <flux:field>
                                <flux:label>{{ __('Does the person live in') }} {{ $this->event->city }}
                                    {{ __('and surrounding area') }}?<span class="text-red-500">*</span>
                                </flux:label>
                                <flux:radio.group wire:key="foreign_city" wire:model.change="form.foreign_city"
                                    variant="buttons" class="w-full *:flex-1">
                                    <flux:radio :value="0" label="{{ __('Yes') }}" />
                                    <flux:radio :value="1" label="{{ __('No') }}" />
                                </flux:radio.group>
                                <flux:error name="form.foreign_city" />
                            </flux:field>
                            @if ($this->form->foreign_city === null)
                                <flux:input wire:key="no_foreign_city" disabled placeholder="{{ __('Please select the field above') }}">
                                </flux:input>
                            @endif

                            @if (isset($this->form->foreign_city) && !$this->form->foreign_city)
                                @if ($this->form->form_fields->postal_code && !$this->form->unknown_postal_code)
                                    <flux:field wire:key="postal_code_field">
                                        <flux:label>{{ __('Postal Code') }}<span class="text-red-500">*</span>
                                        </flux:label>
                                        <flux:input wire:key="postal_code" wire:model="form.postal_code"
                                            type="number" />
                                        <flux:error name="form.postal_code" />
                                    </flux:field>
                                @endif
                                @if ($this->form->form_fields->postal_code && $this->form->form_fields->district)
                                    <flux:field wire:key="unknown_postal_code_field" variant="inline">
                                        <flux:checkbox wire:key="unknown_postal_code" wire:model.live="form.unknown_postal_code" />
                                        <flux:label>{{ __('I don\'t know the postal code') }}</flux:label>
                                    </flux:field>
                                @endif
                                
                                @if ($this->form->form_fields->district && $this->form->unknown_postal_code)
                                    <flux:field wire:key="district_field">
                                        <flux:label>{{ __('District') }}<span class="text-red-500">*</span>
                                        </flux:label>
                                        <flux:select placeholder="{{ __('Select a district') }}" searchable
                                            wire:key="district-{{ $this->form->resetCounter }}"
                                            wire:model="form.district" variant="listbox"
                                            description="{{ __('Select the district where the person lives.') }}">

                                            @foreach ($this->district_form->districts as $district)
                                                <flux:select.option value="{{ $district->id }}"
                                                    wire:key="{{ $district->id }}">
                                                    {{ $district->name }}
                                                </flux:select.option>
                                            @endforeach
                                            <flux:select.option value="other">
                                                {{ __('Other District') }}
                                            </flux:select.option>
                                        </flux:select>
                                    </flux:field>
                                @endif
                            @elseif (isset($this->form->foreign_city) && $this->form->foreign_city)
                                <flux:field wire:key="city_field">
                                    <flux:label>{{ __('City') }}<span class="text-red-500">*</span>
                                    </flux:label>
                                    <flux:input wire:key="city" wire:model="form.city" type="text" />
                                    <flux:error name="form.city" />
                                </flux:field>
                            @endif
                            @if ($this->form->form_fields->language)
                                <flux:field wire:key="language_field">
                                    <flux:label>{{ __('Languages') }}<span class="text-red-500">*</span>
                                    </flux:label>
                                    <flux:pillbox wire:key="language" placeholder="{{ __('Select a language') }}"
                                        searchable wire:model.live="form.language"
                                        description="{{ __('Select all languages the person speaks.') }}" multiple>

                                        @foreach ($this->language_form->languages as $language)
                                            <flux:pillbox.option value="{{ $language->id }}"
                                                wire:key="{{ $language->id }}">
                                                {{ $language->translation->name }}
                                            </flux:pillbox.option>
                                        @endforeach
                                    </flux:pillbox>
                                </flux:field>
                            @endif

                            @if ($this->form->form_fields->gender)
                                <flux:field wire:key="gender_field">
                                    <flux:label>{{ __('Gender') }}<span class="text-red-500">*</span>
                                    </flux:label>
                                    <flux:radio.group wire:key="gender" wire:model="form.gender" variant="buttons"
                                        class="w-full *:flex-1">
                                        <flux:radio value="male">{{ __('Male') }}</flux:radio>
                                        <flux:radio value="female">{{ __('Female') }}</flux:radio>
                                    </flux:radio.group>
                                    <flux:error name="form.gender" />
                                </flux:field>
                            @endif

                            @if ($this->form->form_fields->age)
                                <div wire:key="age_field">
                                    <flux:label>{{ __('Age') }}<span class="text-red-500">*</span>
                                    </flux:label>
                                    <flux:select wire:key="age" wire:model="form.age" variant="listbox"
                                        placeholder="{{ __('Age') }}">
                                        <flux:select.option>U 18</flux:select.option>
                                        <flux:select.option>18 - 25</flux:select.option>
                                        <flux:select.option>25 - 40</flux:select.option>
                                        <flux:select.option>40 - 65</flux:select.option>
                                        <flux:select.option>Ü 65</flux:select.option>
                                    </flux:select>
                                    <flux:error name="form.age" />
                                </div>
                            @endif
                            <flux:field>
                                <flux:label>{{ __('The Person has made a decision for Christ.') }}<span
                                        class="text-red-500">*</span></flux:label>
                                <flux:radio.group wire:key="decision" wire:model="form.decision" variant="buttons"
                                    class="w-full *:flex-1">
                                    <flux:radio value="1">{{ __('Yes') }}</flux:radio>
                                    <flux:radio value="0">{{ __('No') }}</flux:radio>
                                </flux:radio.group>
                                <flux:error name="form.decision" />
                            </flux:field>
                            @if ($this->form->form_fields->evangelist_name)
                                <flux:field wire:key="evangelist_name_field">
                                    <flux:label>Name Evangelist<span class="text-red-500">*</span></flux:label>
                                    <flux:input wire:key="evangelist_name" wire:model="form.evangelist_name"
                                        type="text" autocomplete="name" />
                                    <flux:error name="form.evangelist_name" />
                                </flux:field>
                            @endif

                            <flux:field>
                                <flux:textarea wire:key="comments" wire:model="form.comments"
                                    label="{{ __('Comments') }}" />
                                <flux:error name="form.comments" />
                            </flux:field>
                            <flux:button type="submit" class="bg-cyan-700 hover:bg-cyan-800 w-full"
                                variant="primary">
                                {{ __('Save') }}
                            </flux:button>
                        @endif


                        </form>
                    </flux:tab.panel>
                    <flux:tab.panel class="space-y-6" name="without_contact">
                        <form wire:submit.prevent="addDecisions" class="space-y-6">
                        <flux:field>
                            <flux:label>{{ __('Number of Decisions') }}<span class="text-red-500">*</span>
                            </flux:label>
                            <flux:input wire:key="number_of_decisions" wire:model="form.number_of_decisions"
                                type="number" />
                            <flux:error name="form.number_of_decisions" />
                        </flux:field>
                        @if ($this->form->form_fields->evangelist_name)
                            <flux:field>
                                <flux:label>Name Evangelist<span class="text-red-500">*</span></flux:label>
                                <flux:input wire:key="decision_evangelist_name"
                                    wire:model="form.decision_evangelist_name" type="text" autocomplete="name" />
                                <flux:error name="form.decision_evangelist_name" />
                            </flux:field>
                        @endif
                        <flux:button type="button"
                            class="bg-cyan-700 hover:bg-cyan-800 w-full" variant="primary">
                            {{ __('Save') }}
                        </flux:button>
                    </form>
                    </flux:tab.panel>
                </flux:tab.group>


            </div>
        @endif
        <flux:toast />
    </div>
</div>
