<?php

namespace App\Livewire\Languages;

use Livewire\Component;
use App\Models\Language;
use App\Models\Event;
use Livewire\Attributes\Validate;
use App\Livewire\Forms\LanguageForm;
use Flux\Flux;

class Item extends Component
{
    public LanguageForm $form;
    public ?int $language_id;

    public function mount($language_id, $event) {
        $this->language_id = $language_id;
        $this->form->event = $event;
        $language = $this->language();

        if ($language === null) {
            return;
        }

        $this->form->name = $language->translation?->name ?? '';
        $this->form->language = $language;
    }

    public function language(): ?Language {
        return Language::find($this->language_id);
    }

    public function editItem($id) {
        $this->form->editItem($id);
    }


    public function updateLanguage($id) {
        $this->form->updateLanguage($id);
        $this->dispatch('updateLanguages');
    }

    public function deleteLanguage($id) {
        Flux::modals()->close();
        $this->form->deleteLanguage($id);
        $this->dispatch('updateLanguages');
        Flux::toast(
            heading: __('Language deleted'),
            text: __('The language has been deleted successfully.'),
            variant: 'success',
        );
    }

     public function render()
    {
        return view('livewire.languages.item');
    }
}
