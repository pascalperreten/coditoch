<?php

use Livewire\Component;

new class extends Component {
    public $contact;
    public $event;
    public $ministry;
    public $contact_church;

    public function mount($contact, $event, $ministry)
    {
        $this->contact_church = $contact->church_id;
        $this->contact = $contact;
        $this->event = $event;
        $this->ministry = $ministry;
    }

    public function getContactNumber($churchId)
    {
        return $this->event->contacts()->where('church_id', $churchId)->count();
    }

    public function updatedContactChurch()
    {
        $this->contact->update([
            'church_id' => $this->contact_church,
        ]);
    }
};
?>


<flux:select class="min-w-[20rem]" wire:model.change="contact_church" variant="listbox" placeholder="{{ __('Select a church') }}">

    @if (count($this->event->churches) >= 1)
        @foreach ($this->event->churches as $church)
            <div class="flex gap-2 py-2">
                <div>
                    <flux:tooltip toggleable>
                        <flux:button icon="information-circle" variant="ghost" size="sm" />
                        <flux:tooltip.content class="max-w-[18rem]">
                            <p>{{ $church->description }}</p>
                        </flux:tooltip.content>
                    </flux:tooltip>
                </div>
                <flux:select.option class="p-0" wire:key="{{ $church->id }}" value="{{ $church->id }}">
                    {{ $church->name }}
                </flux:select.option>
                <flux:badge icon="user">
                    {{ $this->getContactNumber($church->id) }}
                </flux:badge>
            </div>
        @endforeach
    @else
        <div class="text-start p-2 space-y-2">
            <flux:text class="text-red-400 text-xs font-bold">
                {{ __('No church yet!') }}</flux:text>
            <flux:link href="{{ route('churches.index', [$this->ministry, $this->event, 'q' => 'create']) }}"
                wire:navigate>
                {{ __('Add Church') }}</flux:link>
        </div>
    @endif


</flux:select>
