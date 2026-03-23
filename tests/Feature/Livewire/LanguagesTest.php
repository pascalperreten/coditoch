<?php

use App\Livewire\Languages\Index as LanguagesIndex;
use App\Livewire\Languages\Item as LanguageItem;
use App\Models\Event;
use App\Models\Language;
use App\Models\LanguageTranslation;
use App\Models\Ministry;
use App\Models\User;
use Livewire\Livewire;

test('language item can be deleted without rendering a null language', function () {
    app()->setLocale('en');

    $user = User::factory()->create();

    $ministry = Ministry::create([
        'name' => 'Connect2Life',
        'user_id' => $user->id,
        'slug' => 'connect2life',
    ]);

    $event = Event::create([
        'ministry_id' => $ministry->id,
        'name' => 'Spring Event',
        'slug' => 'spring-event',
        'city' => 'Bern',
    ]);

    $language = Language::create([
        'event_id' => $event->id,
    ]);

    LanguageTranslation::create([
        'language_id' => $language->id,
        'event_id' => $event->id,
        'locale' => 'en',
        'name' => 'English',
    ]);

    Livewire::test(LanguageItem::class, [
        'language_id' => $language->id,
        'event' => $event,
    ])
        ->call('deleteLanguage', $language->id)
        ->assertDispatched('updateLanguages')
        ->assertHasNoErrors();

    expect($language->fresh())->toBeNull();
});

test('languages index refreshes when the update event is dispatched', function () {
    app()->setLocale('en');

    $user = User::factory()->create();

    $ministry = Ministry::create([
        'name' => 'Hope Ministry',
        'user_id' => $user->id,
        'slug' => 'hope-ministry',
    ]);

    $event = Event::create([
        'ministry_id' => $ministry->id,
        'name' => 'Autumn Event',
        'slug' => 'autumn-event',
        'city' => 'Zurich',
    ]);

    $language = Language::create([
        'event_id' => $event->id,
    ]);

    LanguageTranslation::create([
        'language_id' => $language->id,
        'event_id' => $event->id,
        'locale' => 'en',
        'name' => 'French',
    ]);

    Livewire::test(LanguagesIndex::class, [
        'ministry' => $ministry,
        'event' => $event,
    ])
        ->dispatch('updateLanguages')
        ->assertSee('French')
        ->assertHasNoErrors();
});