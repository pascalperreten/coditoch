<div>
    @if (count($this->members) >= 1)
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Name</flux:table.column>
                <flux:table.column>{{ __('Email') }}</flux:table.column>
                <flux:table.column>{{ __('Phone') }}</flux:table.column>
                <flux:table.column>{{ __('Gender') }}</flux:table.column>
                <flux:table.column>{{ __('Role') }}</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column></flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($this->members as $member)
                    <flux:table.row wire:key="member-{{ $member->id }}">
                        <flux:table.cell>{{ $member->first_name . ' ' . $member->last_name }}
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex gap-4 items-center">
                                <flux:text>{{ $member->email }}</flux:text>
                                <flux:link class="" href="mailto:{{ $member->email }}">
                                    <flux:icon.envelope />
                                </flux:link>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>
                            @if ($member->phone)
                                <div class="flex gap-4 items-center">
                                    <flux:text>{{ $member->phone }}</flux:text>
                                    <flux:link href="tel:{{ $member->phone }}">
                                        <flux:icon.phone-arrow-up-right />
                                    </flux:link>
                                </div>
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                            @if ($member->gender)
                                {{ $member->gender === 'male' ? __('Male') : __('Female') }}
                            @endif
                        </flux:table.cell>
                        <flux:table.cell>
                            {{ $this->setRole($member->role) }}
                        </flux:table.cell>
                        <flux:table.cell>
                            @if (!$member->password)
                                <flux:badge color="red">{{ __('pending') }}</flux:badge>
                            @else
                                <flux:badge color="green">{{ __('active') }}</flux:badge>
                            @endif
                        </flux:table.cell>
                        @can('update', $member)
                            <flux:table.cell class="text-end" inset="top-bottom">
                                <livewire:members.edit wire:key="member-{{ $member->id }}" :ministry="$this->ministry" :church="$this->church"
                                    :member="$member" />
                            </flux:table.cell>
                        @endcan

                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    @else
        <flux:text>{{ __('No members have been added yet.') }}</flux:text>
    @endif

</div>
