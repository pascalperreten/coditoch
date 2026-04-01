<?php

use Livewire\Component;

new class extends Component {
    public $contact;
};
?>

<div>
    <flux:dropdown position="top" align="middle">

        <flux:button class="cursor-pointer" icon:trailing="chat-bubble-oval-left-ellipsis"></flux:button>
        <flux:menu keep-open>
            <div x-data="{ copied: false }" class="p-2">
                <div class="space-y-2">
                    @if ($contact->way_to_get_in_contact === 'phone')
                        <flux:heading>{{ __('Phone') }}</flux:heading>
                    @elseif ($contact->way_to_get_in_contact === 'social_media')
                        <flux:heading>
                            {{ ucfirst($contact->social_media['platform']) }}
                        </flux:heading>
                    @elseif($contact->way_to_get_in_contact === 'email')
                        <flux:heading>{{ __('Email') }}</flux:heading>
                    @elseif ($contact->way_to_get_in_contact === 'other_contact')
                        <flux:heading>{{ __('Other') }}</flux:heading>
                    @endif
                    <flux:separator />
                    <div class="flex justify-between gap-4">
                        @if ($contact->way_to_get_in_contact === 'phone')
                            <flux:text>{{ $contact->phone }}
                            </flux:text>
                            <span class="flex gap-4">
                                <flux:link href="tel:{{ $contact->phone }}">
                                    <flux:icon.phone />
                                </flux:link>
                                <flux:separator vertical />
                                <flux:icon.clipboard class="cursor-pointer justify-self-end" x-show="!copied"
                                    x-on:click="$clipboard('{{ $contact->phone }}');
                                                            copied = true;
                                                            setTimeout(() => copied = false, 2000)" />
                                <flux:icon.clipboard-document-check x-show="copied" />
                            </span>
                        @elseif ($contact->way_to_get_in_contact === 'social_media')
                            <flux:text>
                                {{ ucfirst($contact->social_media['user_name']) }}
                            </flux:text>
                            @if ($contact->social_media['url'])
                                <span class="flex gap-4">
                                    <flux:link target="_blank" href="{{ $contact->social_media['url'] }}">
                                        <flux:icon.globe-alt />
                                    </flux:link>
                                    <flux:separator vertical />
                                    <flux:icon.clipboard class="cursor-pointer justify-self-end" x-show="!copied"
                                        x-on:click="$clipboard('{{ $contact->social_media['url'] }}');
                                                            copied = true;
                                                            setTimeout(() => copied = false, 2000)" />
                                    <flux:icon.clipboard-document-check x-show="copied" />
                                </span>
                            @endif
                        @elseif($contact->way_to_get_in_contact === 'email')
                            <flux:text>{{ $contact->email }}
                            </flux:text>
                            <span class="flex gap-4">
                                <flux:link href="mailto:{{ $contact->email }}">
                                    <flux:icon.envelope />
                                </flux:link>
                                <flux:separator vertical />
                                <flux:icon.clipboard class="cursor-pointer justify-self-end" x-show="!copied"
                                    x-on:click="$clipboard('{{ $contact->email }}');
                                                            copied = true;
                                                            setTimeout(() => copied = false, 2000)" />
                                <flux:icon.clipboard-document-check x-show="copied" />
                            </span>
                        @elseif ($contact->way_to_get_in_contact === 'other_contact')
                            <flux:text>{{ $contact->other_contact }}
                            </flux:text>
                        @endif
                    </div>
                </div>
            </div>
        </flux:menu>
    </flux:dropdown>
</div>
