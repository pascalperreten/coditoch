<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Ministry;
use App\Models\Contact;
use App\Models\Church;
use App\Models\User;
use App\Models\Language;
use App\Models\District;
use App\Models\PostalCode;

class Event extends Model
{
    protected $fillable = [
        'name',
        'city',
        'ministry_id',
        'slug',
        'logo_path',
        'logo_name',
        'active_invitation_link',
        'invitation_token',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected function casts(): array
    {
        return [
            'active_invitation_link' => 'boolean',
        ];
    }

    public function ministry(): BelongsTo {
        return $this->belongsTo(Ministry::class);
    }

    public function churches(): BelongsToMany {
        return $this->belongsToMany(Church::class);
    }

    public function followUpMembers(): BelongsToMany {
        return $this->belongsToMany(User::class, 'event_follow_up');
    }

    public function languages(): HasMany {
        return $this->hasMany(Language::class);
    }

    public function postalCodes(): HasMany {
        return $this->hasMany(PostalCode::class);
    }

    public function contacts(): HasMany {
        return $this->hasMany(Contact::class);
    }

    public function districts(): HasMany {
        return $this->hasMany(District::class);
    }

    public function decisions(): HasMany {
        return $this->hasMany(Decision::class);
    }

    public function contactForm(): HasOne {
        return $this->hasOne(ContactForm::class);
    }

    public function gospelShares(): HasMany {
        return $this->hasMany(GospelShare::class);
    }
}
