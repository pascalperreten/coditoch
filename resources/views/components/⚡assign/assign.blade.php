<div class="space-y-6">
    @if (!$this->newContacts()->isEmpty())
        @if (isset($currentContact))
            <div wire:show="showChurches"
                class="fixed overflow-auto top-0 left-0 w-full max-h-1/2 shadow bg-cyan-700 text-white border-b-2 border-orange-500 p-12 z-10">
                <flux:icon.x-mark wire:click="closeAndResetChurches"
                    class="position cursor-pointer absolute top-10 right-10" />

                <div class="space-y-4">
                    @if ($this->event->churches()->count() >= 1)
                        <flux:heading class="text-xl text-white">{{ __('Church suggestions for') }} <span
                                class="font-bold">{{ $currentContact->name }}</span></flux:heading>
                        <flux:text class="font-bold text-stone-200">{{ __('Same Postal Code') }}</flux:text>
                        <div class="flex gap-4 flex-wrap">
                            @if (count($this->plzChurches) >= 1)
                                @foreach ($this->plzChurches as $church)
                                    <div class="flex items-center gap-2 border rounded-sm px-3 py-2">
                                        <div>
                                            <flux:tooltip toggleable>
                                                <flux:button class="text-white!" icon="information-circle" variant="ghost" size="sm" />
                                                <flux:tooltip.content class="max-w-[20rem] space-y-2">
                                                    <p>{{ $church->description }}</p>
                                                </flux:tooltip.content>
                                            </flux:tooltip>
                                        </div>
                                        <flux:text class="inline text-stone-200">{{ $church->name }}</flux:text>
                                        <flux:badge icon="user" variant="solid" size="sm">
                                            {{ $this->getContactNumber($church->id) }}
                                        </flux:badge>
                                    </div>
                                @endforeach
                            @else
                                <flux:text class="text-stone-300">{{ __('No church with the same postal code') }}
                                </flux:text>
                            @endif

                        </div>
                        <div class="border-b border-stone-200"></div>
                        <flux:text class="font-bold text-stone-200">{{ __('Same district') }}</flux:text>
                        <div class="flex gap-4 flex-wrap">
                            @if (count($this->districtChurches) >= 1)
                                @foreach ($this->districtChurches as $church)
                                    <div class="flex items-center gap-2 border rounded-sm px-3 py-2">
                                        <div>
                                            <flux:tooltip toggleable>
                                                <flux:button class="text-white!" icon="information-circle" variant="ghost" size="sm" />
                                                <flux:tooltip.content class="max-w-[20rem] space-y-2">
                                                    <p>{{ $church->description }}</p>
                                                </flux:tooltip.content>
                                            </flux:tooltip>
                                        </div>
                                        <flux:text class="inline text-stone-200">{{ $church->name }}</flux:text>
                                        <flux:badge icon="user" variant="solid" size="sm">
                                            {{ $this->getContactNumber($church->id) }}
                                        </flux:badge>

                                    </div>
                                @endforeach
                            @else
                                <flux:text class="text-stone-300">{{ __('No church with the same district') }}
                                </flux:text>
                            @endif
                        </div>
                        <div class="border-b border-stone-200"></div>
                        <flux:text class="font-bold text-stone-200">{{ __('Same language') }}</flux:text>
                        <div class="flex gap-4 flex-wrap">
                            @if (count($this->languageChurches) >= 1)
                                @foreach ($this->languageChurches as $church)
                                    <div class="flex items-center gap-2 border rounded-sm px-3 py-2">
                                        <div>
                                            <flux:tooltip toggleable>
                                                <flux:button class="text-white!" icon="information-circle" variant="ghost" size="sm" />
                                                <flux:tooltip.content class="max-w-[20rem] space-y-2">
                                                    <p>{{ $church->description }}</p>
                                                </flux:tooltip.content>
                                            </flux:tooltip>
                                        </div>
                                        <flux:text class="inline text-stone-200">{{ $church->name }}</flux:text>
                                        <flux:badge icon="user" variant="solid" size="sm">
                                            {{ $this->getContactNumber($church->id) }}
                                        </flux:badge>
                                    </div>
                                @endforeach
                            @else
                                <flux:text class="text-stone-300">{{ __('No church with the same language') }}
                                </flux:text>
                            @endif
                        </div>
                    @else
                        <flux:heading class="text-xl text-white">{{ __('There are no Churches yet') }}</flux:heading>
                        <flux:button
                            href="{{ route('churches.index', [$this->ministry, $this->event, 'q' => 'create']) }}"
                            wire:navigate>{{ __('Add Church') }}</flux:button>
                    @endif
                </div>
            </div>
        @endif

        <flux:heading size="lg" class="text-orange-500 border border-orange-500 px-2 py-4 rounded text-center">
            {{ __('You have new Contacts!') }}
        </flux:heading>
        <flux:card>
            <flux:table>
                <flux:table.columns sticky class="bg-white dark:bg-zinc-700">
                    <flux:table.column>{{ __('Name') }}</flux:table.column>
                    <flux:table.column sortable :sorted="$this->sortBy === 'postal_code_name'"
                        :direction="$this->sortDirection" wire:click="sort('postal_code_name')">
                        {{ __('Postal Code') }}
                    </flux:table.column>
                    <flux:table.column sortable :sorted="$this->sortBy === 'district_name'"
                        :direction="$this->sortDirection" wire:click="sort('district_name')">
                        {{ __('District') }}
                    </flux:table.column>
                    <flux:table.column>{{ __('Age') }}</flux:table.column>
                    <flux:table.column>{{ __('Gender') }}</flux:table.column>
                    <flux:table.column>{{ __('Language') }}</flux:table.column>
                    <flux:table.column>{{ __('Infos') }}</flux:table.column>
                    <flux:table.column></flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    <div wire:poll.10s>
                        @foreach ($this->newContacts as $contact)
                            <flux:table.row
                                class="{{ isset($this->currentContact) && $this->currentContact->id === $contact->id ? 'bg-orange-700/10' : '' }}"
                                wire:key="contact-{{ $contact->id }}">
                                <flux:table.cell>
                                    {{ $contact->name }}</flux:table.cell>
                                <flux:table.cell>
                                    @if (!$contact->postalCode()->exists())
                                        {{ 'keine' }}
                                    @else
                                        <a class="underline" target="_blank"
                                            href="{{ $this->postalCodeUrl($contact) }}">{{ $contact->postalCode->first()->name ?? 'keine' }}</a>
                                    @endif
                                </flux:table.cell>
                                <flux:table.cell>
                                    @if (!$contact->district()->exists())
                                        {{ 'keine' }}
                                    @else
                                        <a target="_blank" class="underline"
                                            href="{{ $this->districtUrl($contact) }}">{{ $contact->district->first()->name ?? 'keine' }}</a>
                                    @endif
                                </flux:table.cell>
                                <flux:table.cell>{{ $contact->age ?? 'keine' }}</flux:table.cell>
                                <flux:table.cell>
                                    @if ($contact->gender)
                                        @if ($contact->gender === 'male')
                                            {{ __('Man') }}
                                        @else
                                            {{ __('Woman') }}
                                        @endif
                                    @else
                                        {{ 'keine' }}
                                    @endif
                                </flux:table.cell>
                                <flux:table.cell>
                                    @foreach ($contact->languages as $key => $language)
                                        @if (count($contact->languages) === 1)
                                            {{ $language->translation->name }}
                                        @elseif ($key === count($contact->languages) - 1)
                                            {{ $language->translation->name }}
                                        @else
                                            {{ $language->translation->name }},
                                        @endif
                                    @endforeach
                                </flux:table.cell>
                                <flux:table.cell>
                                    @if ($contact->comments)
                                        <flux:modal.trigger name="contact-{{ $contact->id }}-info">
                                        <flux:button icon="information-circle" />
                                        </flux:modal.trigger>

                                        <flux:modal name="contact-{{ $contact->id }}-info">
                                            <flux:heading size="lg">{{ __('Comments') }}</flux:heading>
                                            {{ $contact->comments }}
                                        </flux:modal>
                                    @endif
                                    
                                    
                                </flux:table.cell>

                                <flux:table.cell align="end" class="flex gap-2">
                                    <flux:button wire:click="checkChurches({{ $contact->id }})">
                                        {{ __('Churches') }}
                                    </flux:button>
                                    <livewire:assign-church :contact="$contact" :event="$event" :ministry="$ministry" />
                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </div>
                </flux:table.rows>
            </flux:table>
            <div class="flex">
                <flux:spacer />
                <flux:modal.trigger name="assign-church">
                    <flux:button variant="primary" class="cursor-pointer">{{ __('Complete assignment') }}
                    </flux:button>
                </flux:modal.trigger>
                <flux:modal name="assign-church" class="max-w-sm">
                    <flux:heading>
                        {{ __('If you submit the assignment, all churches will receive an email notification.') }}
                    </flux:heading>
                    <flux:button class="mt-6" type="button" wire:click="updateChurch">
                        {{ __('Complete assignment') }}</flux:button>
                </flux:modal>

            </div>
        </flux:card>
    @endif
    @if (!$this->newForeignContacts->isEmpty())

        <flux:heading size="lg" class=" border px-2 py-4 rounded text-center">
            {{ __('Contacts from another City!') }}
        </flux:heading>
        <flux:card>
            <div>
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Name</flux:table.column>
                        <flux:table.column>{{ __('City') }}</flux:table.column>
                        <flux:table.column>{{ __('Age') }}</flux:table.column>
                        <flux:table.column>{{ __('Gender') }}</flux:table.column>
                        <flux:table.column>{{ __('Language') }}</flux:table.column>
                        <flux:table.column>{{ __('Contact') }}</flux:table.column>
                        <flux:table.column></flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows wire:poll.10s>

                        @foreach ($this->newForeignContacts as $contact)
                            <flux:table.row>

                                <flux:table.cell>
                                    {{ $contact->name }}</flux:table.cell>
                                <flux:table.cell>
                                    {{ $contact->city }}
                                </flux:table.cell>
                                <flux:table.cell>{{ $contact->age ?? 'keine' }}</flux:table.cell>
                                <flux:table.cell>
                                    @if ($contact->gender === 'male')
                                        {{ __('Man') }}
                                    @else
                                        {{ __('Woman') }}
                                    @endif
                                </flux:table.cell>
                                <flux:table.cell>
                                    @foreach ($contact->languages as $key => $language)
                                        @if (count($contact->languages) === 1)
                                            {{ $language->translation->name }}
                                        @elseif ($key === count($contact->languages) - 1)
                                            {{ $language->translation->name }}
                                        @else
                                            {{ $language->translation->name }},
                                        @endif
                                    @endforeach
                                </flux:table.cell>
                                <flux:table.cell>
                                    <div class="flex items-center gap-4">
                                        <livewire:contact-info :contact="$contact" />
                                    </div>
                                </flux:table.cell>

                                <flux:table.cell align="end">
                                    <flux:modal.trigger name="contact-submitted-{{ $contact->id }}">
                                        <flux:button icon="cog-6-tooth" class="cursor-pointer" />
                                    </flux:modal.trigger>
                                    <flux:modal name="contact-submitted-{{ $contact->id }}" class="max-w-sm">
                                        <div class="space-y-4 text-start">
                                            <flux:heading size="lg">{{ $contact->name }}</flux:heading>
                                            <flux:heading>{{ __('Edit Contact') }}</flux:heading>
                                            <div class="flex items-center gap-4">
                                                <flux:text>Falls die Person doch aus {{ $this->event->city }} kommt,
                                                    kannst
                                                    du das hier bearbeiten
                                                </flux:text>
                                                <flux:button variant="ghost" wire:navigate
                                                    href="{{ route('contacts.edit', [$this->ministry, $this->event, $contact]) }}"
                                                    icon="pencil-square" />
                                            </div>
                                            <flux:separator />
                                            <flux:text>{{ __('Were you able to connect the person to a church?') }}
                                            </flux:text>
                                            <form wire:submit.prevent="assignChurchName({{ $contact->id }})">
                                                <flux:field>
                                                    <flux:label>{{ __('Name Church') }}</flux:label>
                                                    <flux:input type="text" wire:model="church_name"
                                                        placeholder="Name der Kirche" />
                                                    <flux:error name="church_name" />
                                                </flux:field>
                                                <div class="flex justify-end mt-6">
                                                    <flux:button variant="primary" class="cursor-pointer"
                                                        type="submit">
                                                        {{ __('Save') }}</flux:button>
                                                </div>
                                            </form>
                                        </div>


                                    </flux:modal>

                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
                <div>
        </flux:card>
    @endif
</div>
