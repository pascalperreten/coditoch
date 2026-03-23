<?php

namespace App\Livewire\PostalCodes;

use Livewire\Component;
use App\Models\PostalCode;
use App\Models\Event;
use Livewire\Attributes\Validate;
use App\Livewire\Forms\PostalCodeForm;

class Item extends Component
{
    public PostalCodeForm $form;
    public ?int $postal_code_id = null;

    public function mount($postal_code_id, $event) {
        $this->form->event = $event;
        $this->postal_code_id = $postal_code_id;
        $this->form->name = $this->postal_code()?->name;
        $this->form->postal_code = $this->postal_code();
    }

    public function postal_code(): ?PostalCode {
        return PostalCode::find($this->postal_code_id);
    }

    public function editItem($id) {
        $this->form->editItem($id);
    }


    public function updatePostalCode($id) {
        $this->form->updatePostalCode($id);
        $this->dispatch('updatePostalCode');
    }

    public function deletePostalCode($id) {
        $this->form->deletePostalCode($id);
        $this->dispatch('updatePostalCodes');
    }
    public function render()
    {
        return view('livewire.postal-codes.item');
    }
}
