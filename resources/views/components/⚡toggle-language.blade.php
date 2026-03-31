<?php

use Livewire\Component;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

new class extends Component {
    public $locale;

    public function mount()
    {
        $this->locale = auth()->check() ? auth()->user()->locale : Cookie::get('locale', config('app.locale'));
        App::setLocale($this->locale);
    }

    public function switchLanguage($lang)
    {
        if (auth()->check()) {
            auth()->user()->update(['locale' => $lang]);
        }

        Cookie::queue(Cookie::forever('locale', $lang));

        //$this->locale = $lang;
        return $this->redirect(request()->header('Referer') ?? url('/'), navigate: true);
;
    }
};
?>

<div>
    <flux:radio.group wire:model="locale" variant="segmented">
        <flux:radio wire:click="switchLanguage('de')" value="de" label="DE" />
        <flux:radio wire:click="switchLanguage('en')" value="en" label="EN" />
    </flux:radio.group>

</div>
