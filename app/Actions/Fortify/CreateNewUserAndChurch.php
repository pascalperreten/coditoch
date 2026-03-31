<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Ministry;
use App\Models\Church;
use App\Models\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CreateNewUserAndChurch implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {

        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'phone' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:25'],
            'church_name' => [
                'required', 
                'string', 
                'max:255',
                Rule::unique(Church::class, 'name')->where('event_id', $input['event_id']),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        $user = User::create([
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'email' => $input['email'],
            'password' => $input['password'],
            'role' => $input['role'],
            'locale' => config('app.locale'),
            'phone' => $input['phone'],
        ]);

        $church = Church::create([
            'name' => $input['church_name'],
            'street' => '',
            'postal_code' => '',
            'city' => '',
            'event_id' => $input['event_id'],
            'follow_up_contact' => $user->id,
            'slug' => Str::slug($input['church_name']),
        ]);

        $user->update(['church_id' => $church->id]);
        
    
        $church->events()->attach($input['event_id']);

        Auth::login($user);

        return $user;
    }
}

