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
        @foreach ($this->event->churches()->orderBy('name')->get() as $church)
            <div class="flex gap-2 py-2 items-center">
                <div>
                    <flux:tooltip toggleable>
                        <flux:button icon="information-circle" variant="ghost" size="sm" />
                        <flux:tooltip.content class="max-w-[18rem] text-left">
                            <p class="text-white">{{ __('Number of Follow Up people') }}: {{ $church->members->count() }}</p>
                            <p>{{ __('Men') }}: {{ $church->members->where('gender', 'male')->count() }}</p>
                            <p>{{ __('Women') }}: {{ $church->members->where('gender', 'female')->count() }}</p>
                            <h6 class="text-white font-bold mt-2">{{ __('Languages') }}</h6>
                            <p class="text-white">
                                @foreach ($church->languages as $key => $language)
                                    {{ $key === 0 ? '' : '| ' }}{{ $language->translation->name }}
                                @endforeach
                            </p>
                            <h6 class="text-white font-bold mt-2">{{ __('Description') }}</h6>
                            <p class="text-white">{{ $church->description }}</p>
                        </flux:tooltip.content>
                    </flux:tooltip>
                </div>
                <flux:select.option class="p-0" wire:key="{{ $church->id }}" value="{{ $church->id }}">
                    {{ $church->name }}
                </flux:select.option>
                <flux:tooltip toggleable>
                    <flux:button size="sm" icon="user">
                        {{ $this->getContactNumber($church->id) }}
                    </flux:button>
                    <flux:tooltip.content class="max-w-[20rem] space-y-2 text-left">
                        <p>{{ __('Men') }}: {{ $church->contacts->where('gender', 'male')->count() }}</p>
                        <p>{{ __('Women') }}: {{ $church->contacts->where('gender', 'female')->count() }}</p>
                    </flux:tooltip.content>
                </flux:tooltip>
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
