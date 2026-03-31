<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
//use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Ministry;
use App\Models\Church;
use App\Models\Event;
use App\Models\Role;
use App\Models\Contact;

class User extends Authenticatable implements MustVerifyEmail, HasLocalePreference
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'phone',
        'avatar_path',
        'avatar_name',
        'ministry_id',
        'event_id',
        'church_id',
        'invitation_token',
        'locale',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function preferredLocale()
    {
        return $this->locale ?? config('app.locale');
    }

    public function ministry(): BelongsTo {
        return $this->belongsTo(Ministry::class);
    }

    public function role(): HasOne {
        return $this->hasOne(Role::class);
    }

    public function contacts(): HasMany {
        return $this->hasMany(Contact::class, 'follow_up_person');
    }

    public function church(): BelongsTo {
        return $this->belongsTo(Church::class);
    }
    public function events(): BelongsToMany {
        return $this->belongsToMany(Event::class, 'event_follow_up');
    }
    
}
