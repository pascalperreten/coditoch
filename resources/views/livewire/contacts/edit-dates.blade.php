<div>
    <flux:modal.trigger name="edit-{{ $contact->id }}">
        <flux:button class="cursor-pointer" icon:trailing="cog-8-tooth" />
    </flux:modal.trigger>

    <flux:modal wire:key="{{ $contact->id }}" name="edit-{{ $contact->id }}" class="text-left">
        <div class="space-y-4">
            <flux:heading size="xl">{{ $contact->name }}</flux:heading>
            <flux:separator />



            <form class="space-y-6">
                @can('update', $contact->church)
                    <flux:select variant="listbox" label="{{ __('Follow-Up Person') }}"
                        wire:model.change="form.follow_up_person" placeholder="{{ __('Select a follow-up person') }}"
                        description="{{ __('Who will follow up with this person?') }}">
                        @foreach ($contact->church->members as $member)
                            <flux:select.option :value="$member->id">
                                {{ $member->first_name . ' ' . $member->last_name }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:button wire:show="newFollowUpPerson" wire:click="saveFollowUpPerson" variant="outline">{{ __('Save Follow-Up Person') }}</flux:button>
                @endcan

                <flux:heading size="lg">{{ __('Get in touch') }}</flux:heading>

                <flux:radio.group wire:model.lazy="form.invalid_contact_details"
                    label="{{ __('Contact details correct') }}" variant="cards" class="max-sm:flex-col">
                    <flux:radio :value="0" label="{{ __('Yes') }}"
                        description="{{ __('Were you able to reach the person using the contact details provided?') }}" />
                    <flux:radio :value="1" label="{{ __('No') }}"
                        description="{{ __('Were the contact details incorrect and you were unable to get in touch?') }}" />
                </flux:radio.group>
                @if (!$this->form->met && !$this->form->not_interested)
                @endif

                @if (!$this->form->invalid_contact_details)
                    <flux:checkbox.group variant="cards">
                        <flux:checkbox wire:model.lazy="form.decision" label="{{ __('Decision for Christ') }}"
                            description="{{ __('The Person has made a decision for Christ.') }}" />
                    </flux:checkbox.group>

                    <flux:field>
                        <flux:label>{{ __('First attempt to make contact') }}</flux:label>
                        <flux:description>
                            {{ __('Please fill this out even if you were unable to reach the person.') }}
                        </flux:description>
                        <div class="flex">
                            <flux:date-picker locale="{{ app()->getLocale() }}" with-today
                                placeholder="{{ __('Select Date') }}" class="flex-grow"
                                wire:model.lazy="form.contacted_date" />
                            @if ($this->form->contacted_date)
                                <flux:button wire:click="resetContacted">{{ __('Reset') }}</flux:button>
                            @endif

                        </div>
                        <flux:error name="form.contacted_date" />
                    </flux:field>

                    @if (isset($this->form->contacted_date))
                        <flux:radio.group wire:model.lazy="form.not_reached"
                            label="{{ __('Did you reach the person?') }}" variant="cards" class="max-sm:flex-col">
                            <flux:radio :value="0" label="{{ __('Yes') }}"
                                description="{{ __('You were able to reach the person.') }}" />
                            <flux:radio :value="1" label="{{ __('No') }}"
                                description="{{ __('You were not able to reach the person.') }}" />
                        </flux:radio.group>
                        @if (!$this->form->not_reached)
                            
                            <flux:field>
                                <flux:label>{{ __('Meeting') }}</flux:label>

                                <flux:description>{{ __('When do you want to meet?') }}</flux:description>
                                <div class="flex">
                                    <flux:date-picker locale="{{ app()->getLocale() }}"
                                        placeholder="{{ __('Select Date') }}" with-today
                                        :min="$this->form->contacted_date->format('Y-m-d')" class="flex-grow"
                                        wire:model.lazy="form.meeting_date" />
                                    @if (isset($this->form->meeting_date))
                                        <flux:button wire:click="resetMeeting">{{ __('Reset') }}</flux:button>
                                    @endif

                                </div>
                                <flux:error name="form.meeting_date" />
                            </flux:field>
                        
                            @if (!$this->form->met && !isset($this->form->meeting_date))
                                <flux:checkbox.group variant="cards">
                                    <flux:checkbox wire:model.lazy="form.not_interested"
                                        label="{{ __('Does not want to meet') }}"
                                        description="{{ __('You got in touch with the person, but the person is not interested in meeting up with you?') }}" />
                                </flux:checkbox.group>
                            @endif
                        @endif
                    @endif

                    @if ($this->form->meeting_date && !$this->form->not_interested)
                        <flux:checkbox.group variant="cards">
                            <flux:checkbox wire:model.lazy="form.met" label="{{ __('Met') }}"
                                description="{{ __('Did you meet up with that person?') }}" />
                        </flux:checkbox.group>

                        @if ($this->form->met)
                            <flux:checkbox.group variant="cards">
                                <flux:checkbox wire:model.lazy="form.part_of_church" label="{{ __('Part of Church') }}"
                                    description="{{ __('Is this person part of the church, or part of a small group?') }}" />
                            </flux:checkbox.group>
                        @endif
                    @endif
                @endif
            </form>
        </div>
    </flux:modal>
</div>
