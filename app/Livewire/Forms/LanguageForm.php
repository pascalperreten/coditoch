<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use App\Models\Event;
use App\Models\Language;
use App\Models\LanguageTranslation;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Flux\Flux;
use Illuminate\Support\Str;
use Illuminate\Database\Query\Builder;
use Livewire\Form;
use DeepL\Translator;
use DeepL\DeepLClient;
use Illuminate\Validation\ValidationException;

class LanguageForm extends Form
{

    public Event $event;
    public ?Language $language = null;

    public $name = '';
    public $languages = [];
    public $edit = [];

    protected function rules() {

        $unique = Rule::unique('language_translations', 'name')
        ->where(function ($query) {
            $query->where('event_id', $this->event->id);

            if (isset($this->language)) {
                $query->where('language_id', '!=', $this->language->id);
            }
        });

        // $unique = Rule::unique('language_translations')->where('event_id', $this->event->id);

        // if (isset($this->language)) {
        //     $query->where('language_id', '!=', $this->language->id);
        // }
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                $unique,
            ]
            ];
    }

    public function editItem($id) {
        $this->edit = [
            'language' => $id,
        ];
    }

    public function setLanguages($event) {
        //$this->languages = Language::where('event_id', $event->id)->with('translation')->get();
        $this->languages = Language::where('languages.event_id', $event->id)
            ->join('language_translations', function($join) {
                $join->on('languages.id', '=', 'language_translations.language_id')
                    ->where('language_translations.locale', app()->getLocale());
            })
            ->orderBy('language_translations.name')
            ->select('languages.*')
            ->with('translation')
            ->get();
    }

    public function addLanguage() {
        $this->name = ucfirst($this->name);
        $this->validateOnly('name');

        $translator = new DeepLClient(env('DEEPL_KEY'));
        
        $validLanguagesGerman = config("languages.de");
        $validLanguagesEnglish = config("languages.en");
        
        if(app()->getLocale() === 'de' && in_array(Str::lower($this->name), $validLanguagesGerman)) {
            $german_name = ucfirst($this->name);
            $english_name = ucfirst($translator->translateText($this->name, 'DE', 'EN-US')->text);
        } elseif(app()->getLocale() === 'en' && in_array(Str::lower($this->name), $validLanguagesEnglish)) {
            $english_name = ucfirst($this->name);
            $german_name = ucfirst($translator->translateText($this->name, 'EN', 'DE')->text);
        } else {
            throw ValidationException::withMessages([
                'form.name' => __('Please enter a valid language in ') . (app()->getLocale() === 'de' ? __('Deutsch') : __('English')),
            ]);
        }
        
        $newLanguage = DB::transaction(function () use ($german_name, $english_name) {
            $newLanguage = Language::create([
                'event_id' => $this->event->id,
            ]);

            $newLanguage->translations()->createMany([
                [
                    'locale' => 'de',
                    'name' => $german_name,
                    'event_id' => $this->event->id,
                ],
                [
                    'locale' => 'en',
                    'name' => $english_name,
                    'event_id' => $this->event->id,
                ],
            ]);

            return $newLanguage;
        });

        Flux::toast(
            heading: __('Language added'),
            text: __('The Language has been added successfully.'),
            variant: 'success',
        );

        $this->setLanguages($this->event);
        $this->reset('name');
        return $newLanguage;
    }

    public function updateLanguage($id) {
        $this->name = ucfirst($this->name);
        $this->validateOnly('name');

        $translator = new DeepLClient(env('DEEPL_KEY'));

        $validLanguagesGerman = config("languages.de");
        $validLanguagesEnglish = config("languages.en");
        
        if(app()->getLocale() === 'de' && in_array(Str::lower($this->name), $validLanguagesGerman)) {
            $german_name = ucfirst($this->name);
            $english_name = ucfirst($translator->translateText($this->name, 'DE', 'EN-US')->text);
        } elseif(app()->getLocale() === 'en' && in_array(Str::lower($this->name), $validLanguagesEnglish)) {
            $english_name = ucfirst($this->name);
            $german_name = ucfirst($translator->translateText($this->name, 'EN', 'DE')->text);
        } else {
            throw ValidationException::withMessages([
                'form.name' => __('Please enter a valid language in ') . (app()->getLocale() === 'de' ? __('Deutsch') : __('English')),
            ]);
        }

        $languages = LanguageTranslation::where('language_id', $id)->get();
        foreach ($languages as $translation) {
            if($translation->locale === 'de') {
                $translation->update([
                    'name' => $german_name,
                ]);
            } elseif($translation->locale === 'en') {
                $translation->update([
                    'name' => $english_name,
                ]);
            }
        }
    
        Flux::toast(
            heading: __('Language updated'),
            text: __('The language has been updated successfully.'),
            variant: 'success',
        );

        $this->edit = [];
    }

    public function deleteLanguage($id) {
        Language::where('id', $id)->delete();
        $this->setLanguages($this->event);
        $this->language = null;
        $this->edit = [];
    }
}
