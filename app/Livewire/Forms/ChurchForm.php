<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Church;
use App\Models\Event;
use App\Models\District;
use App\Models\Language;
use Illuminate\Support\Str;

class ChurchForm extends Form
{
    use AuthorizesRequests;
    public ?Church $church;

    #[Validate('required|string')]
    public $name;

    #[Validate('required|string')]
    public $street;

    #[Validate('required|string')]
    public $city;

    #[Validate('required|int')]
    public $postal_code;

    #[Validate('required|array')]
    public $districts;

    #[Validate('required|array')]
    public $languages;

    #[Validate('required|array')]
    public $postal_codes;

    #[Validate('nullable|url')]
    public $website_url;

    #[Validate('required|integer|exists:events,id')]
    public $event_id;

    public $slug = '';

    public function setChurch(Church $church) {
        $this->name = $church->name;
        $this->street = $church->street;
        $this->city = $church->city;
        $this->postal_code = $church->postal_code;
        $this->website_url = $church->website_url;
        $this->districts = $church->districts()->pluck('districts.id')->toArray();
        $this->languages = $church->languages()->pluck('languages.id')->toArray();
        $this->postal_codes = $church->postalCodes()->pluck('postal_codes.id')->toArray();
        //$this->event_id = $church->event_id;
    }

    public function create(Event $event) {
        $this->authorize('create', Church::class);
        $this->event_id = $event->id;
        $this->validate();
        $this->slug = Str::slug($this->name);
        

        $church = Church::create(
            $this->only(['name', 'city', 'street', 'postal_code', 'event_id', 'website_url', 'slug'])
        );
        $church->languages()->sync($this->languages);
        $church->districts()->sync($this->districts);
        $church->postalCodes()->sync($this->postal_codes);
        $church->events()->attach($event->id);
        

        session()->flash('success', 'Church created successfully!');
    }

    public function update(Church $church) {
        $this->authorize('update', $church);
        $this->validate();

        $church->update(
            $this->only(['name', 'city', 'street', 'postal_code', 'website_url'])
        );
        $church->languages()->sync($this->languages);
        $church->districts()->sync($this->districts);
        $church->postalCodes()->sync($this->postal_codes);
    }
}
