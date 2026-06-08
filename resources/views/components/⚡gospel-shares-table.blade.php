<?php

use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;
    public $model;

    public function date($date)
    {
        return \Carbon\Carbon::parse($date)->format('d.m.Y | H:i');
    }

    #[\Livewire\Attributes\Computed]
    public function gospelShares()
    {
        return $this->model->gospelShares()->latest()->paginate(100);
    }
};
?>

<div>
    <flux:table :paginate="$this->gospelShares">
        <flux:table.columns>

            <flux:table.column>{{ __('Date') }}</flux:table.column>
            <flux:table.column>{{ __('Name Evangelist') }}</flux:table.column>
            <flux:table.column align="end">{{ __('Number') }}</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->gospelShares as $shares)
                <flux:table.row :key="$shares->id">
                    <flux:table.cell>{{ $this->date($shares->created_at) }}</flux:table.cell>
                    <flux:table.cell>
                        {{ $shares->evangelist_name }}
                    </flux:table.cell>
                    <flux:table.cell align="end">
                        {{ $shares->number_of_gospel_shares }}
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
