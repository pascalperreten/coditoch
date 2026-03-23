<?php
use App\Models\User;
use App\Models\Ministry;
use App\Models\Event;
use App\Models\Contact;
use App\Models\Church;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use App\Livewire\Invitations\Member as InviteMember;
use App\Livewire\Invitations\Church as InviteChurch;
use App\Livewire\Dashboard;
use App\Livewire\Ministry\Index as MinistryIndex;
use App\Livewire\Ministry\Edit as MinistryEdit;
use App\Livewire\Ministry\Members as MinistryMembers;
use App\Livewire\Events\Index as EventIndex;
use App\Livewire\Events\Create as EventCreate;
use App\Livewire\Events\Edit as EventEdit;
use App\Livewire\Events\Manage as EventManage;
use App\Livewire\Events\Show as EventShow;
use App\Livewire\Churches\Index as ChurchIndex;
use App\Livewire\Churches\Create as ChurchCreate;
use App\Livewire\Churches\Show as ChurchShow;
use App\Livewire\Churches\Manage as ChurchManage;
use App\Livewire\Churches\Members as ChurchMembers;
use App\Livewire\Contacts\Form\Index as ContactFormIndex;
use App\Livewire\Contacts\Index as ContactIndex;
use App\Livewire\Contacts\Create as ContactCreate;
use App\Livewire\Contacts\Show as ContactShow;
use App\Livewire\Contacts\Edit as ContactEdit;
use App\Livewire\Languages\Index as Languages;
use App\Livewire\Districts\Index as Districts;
use App\Livewire\PostalCodes\Index as PostalCodes;
use App\Livewire\Manage\Evangelize;
use App\Livewire\Manage\FollowUp;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\RegisteredUserAndChurchController;


Route::get('/mail', function () {
    app()->setLocale('de');
    $user = User::first();
    $notification = new App\Notifications\Test();
    return $notification->toMail($user)->render();
});


Route::middleware(['set.locale'])->group(function () {

    Route::middleware(['auth'])->group(function () {
        Route::redirect('settings', 'settings/profile');

        Route::livewire('settings/profile', Profile::class)->name('profile.edit');
        Route::livewire('settings/password', Password::class)->name('user-password.edit');
        Route::livewire('settings/appearance', Appearance::class)->name('appearance.edit');

        Route::livewire('settings/two-factor', TwoFactor::class)
            ->middleware(
                when(
                    Features::canManageTwoFactorAuthentication()
                        && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                    ['password.confirm'],
                    [],
                ),
            )
            ->name('two-factor.show');
    });

    Route::livewire('{ministry}/invitation/{token}', InviteMember::class)->name('invitation');
    Route::livewire('{ministry}/{event}/invitation/church/{token}', InviteChurch::class)->name('invitation.church')->middleware('signed');
    Route::post('{ministry}/{event}/invitation/church/{token}', [RegisteredUserAndChurchController::class, 'storeUserAndChurch'])->name('register.storeUserAndChurch');
    Route::livewire('{ministry}/{event}/evangelize', ContactCreate::class)->name('events.evangelize')->middleware('signed');
    Route::livewire('{ministry}/{event}/evangelize/gospel-shares', 'pages::evangelize.gospel-shares')->name('evangelize.gospel-shares')->middleware('signed');
    Route::livewire('{ministry}/{event}/{church}/evangelize', ContactCreate::class)->name('churches.evangelize')->middleware('signed');
    //Route::livewire('{church}/invitation/{token}', [Invitation::class, 'invite_church'])->name('church.invitation');

    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::get('dashboard', function () {
        $user = auth()->user();

        $ministry = $user->ministry;

        // if the user doesn’t have a ministry_id
        if (!$ministry && $user->church) {

            // pick the first ministry from the church's events
            $ministry = $user->church->events()
                ->with('ministry') // eager load
                ->get()
                ->pluck('ministry') // collect ministries
                ->first(); // pick the first one
        }
        return redirect()->route('ministry', $ministry);
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::prefix('{ministry}')->scopeBindings()->middleware(['auth', 'verified', 'ensure.ministry'])->group(function() {
        
        Route::middleware(['redirect.dashboard', 'can:view,ministry'])->group(function() {
            Route::livewire('/', MinistryIndex::class)->name('ministry');
            Route::livewire('details', MinistryEdit::class)->name('ministry.details')->can('update', 'ministry');
            Route::livewire('members', MinistryMembers::class)->name('ministry.members')->can('update', 'ministry');
            Route::livewire('events', EventIndex::class)->name('events.index');
            Route::livewire('events/create', EventCreate::class)->name('events.create')->can('update', 'ministry');
            Route::livewire('stats', 'pages::ministry.stats')->name('ministry.stats')->can('update', 'ministry');
            Route::livewire('gospel-shares', 'pages::ministry.gospel-shares')->name('ministry.gospel-shares')->can('update', 'ministry');
        });
            
        Route::prefix('{event}')->scopeBindings()->group(function() {
            Route::middleware(['redirect.event', 'can:view,event'])->group(function() {
                Route::livewire('/', EventShow::class)->name('events.show');
                Route::livewire('manage', EventManage::class)->name('events.manage')->can('update', 'event');
                Route::livewire('stats', 'pages::event.stats')->name('events.stats')->can('view', 'event');
                Route::livewire('gospel-shares', 'pages::event.gospel-shares')->name('events.gospel-shares')->can('view', 'event');
                Route::livewire('details', EventEdit::class)->name('events.details')->can('update', 'event');
                Route::livewire('contacts', ContactIndex::class)->name('contacts.index');
                Route::livewire('contacts/details', 'pages::contact.details')->name('events.contacts.details');
                Route::livewire('contacts/{contact}', ContactShow::class)->name('contacts.show');
                Route::livewire('contacts/{contact}/edit', ContactEdit::class)->name('contacts.edit')->can('update', 'contact');
                Route::livewire('churches', ChurchIndex::class)->name('churches.index');
                Route::livewire('churches/create', ChurchCreate::class)->name('churches.create');
            });
            
            Route::prefix('{church}')->scopeBindings()->middleware('can:view,church')->group(function() {
                Route::livewire('/', ChurchShow::class)->name('churches.show')->middleware('redirect.church')->can('update', 'church');
                Route::livewire('manage', ChurchManage::class)->name('churches.manage')->can('update', 'church');
                Route::livewire('stats', 'pages::church.stats')->name('churches.stats')->can('update', 'church');
                Route::livewire('members', ChurchMembers::class)->name('churches.members')->can('update', 'church');
                Route::livewire('contacts', 'pages::church.contact')->name('churches.contacts')->can('view', 'church');
            });
        });
    });
});
