<?php

namespace App\Http\Controllers;


use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Routing\Controller;
use App\Actions\Fortify\CreateNewUserAndChurch;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Str;

class RegisteredUserAndChurchController extends Controller
{

     /**
     * The guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected $guard;
    public $event;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
     * @return void
     */
    public function __construct(StatefulGuard $guard)
    {
        $this->guard = $guard;
        $this->event = request()->route('event');
    }

    public function storeUserAndChurch(Request $request,
                          CreateNewUserAndChurch $creator): RegisterResponse
    {
        if (config('fortify.lowercase_usernames') && $request->has(Fortify::username())) {
            $request->merge([
                Fortify::username() => Str::lower($request->{Fortify::username()}),
            ]);
        }

        event(new Registered($user = $creator->create($request->all())));

        $this->guard->login($user, $request->boolean('remember'));

        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        return app(RegisterResponse::class);
    }
}
