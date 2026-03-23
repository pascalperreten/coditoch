<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Models\Event;
use App\Models\ManageFollowUp;
use Illuminate\Validation\Rule;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;

class EventForm extends Form
{
    public ?Event $event;

    #[Validate('required|string|max:255')]
    public $name;

    #[Validate('required|string|max:255')]
    public $city;

    public $slug = '';

    public $invitation_token = null;

    #[Validate('required|integer|exists:ministries,id')]
    public $ministry_id;

    public function setEvent(Event $event) {
        $this->event = $event;
        $this->name = $event->name;
        $this->city = $event->city;
    }

    protected function rules()
    {
        $unique = Rule::unique('events', 'slug')->where('ministry_id', $this->ministry_id);

        if(isset($this->event))
            $unique->ignore($this->event->id);
        return [
            'slug' => [
                $unique,
            ],
            'ministry_id' => 'required|exists:ministries,id',
        ];
    }

    public function messages()
    {
        return [
            'slug.unique' =>
                __('This event already exists'),
        ];
    }

    public function create() {
        
        $this->ministry_id = auth()->user()->ministry_id;
        $this->slug = Str::slug($this->name . '-' . $this->city);
        $this->invitation_token = Str::random(20);
        $this->validate();

        $event = Event::create(
            $this->only(['name', 'city', 'ministry_id', 'slug', 'invitation_token'])
        );

        ManageFollowUp::create([
            'event_id' => $event->id,
            'language' => true,
            'age' => true,
            'gender' => true,
            'location' => true,
            'evangelist_name' => true,
            'church_evangelize' => false,
            'assign_directly' => false,
        ]);
    }

    public function update() {
        $this->ministry_id = auth()->user()->ministry_id;
        $this->slug = Str::slug($this->name . '-' . $this->city);
        $this->validate();
        

        $this->event->update(
            $this->only(['name', 'city', 'slug'])
        );
        
    }
}
