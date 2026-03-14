<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use App\Models\District;
use App\Models\Language;
use App\Models\User;
use App\Models\PostalCode;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Church extends Model
{
     public function getRouteKeyName()
    {
        return 'slug';
    }
    
    protected $fillable = [
        'name',
        'street',
        'postal_code',
        'city',
        'event_id',
        'website_url',
        'districts',
        'slug',
        'follow_up_contact',
    ];

   

    public function event(): BelongsTo {
        return $this->belongsTo(Event::class);
    }
    public function events(): BelongsToMany {
        return $this->belongsToMany(Event::class);
    }
    public function members(): HasMany {
        return $this->hasMany(User::class);
    }
    public function contacts(): HasMany {
        return $this->hasMany(Contact::class);
    }
    public function contactsEvangelized(): HasMany {
        return $this->hasMany(Contact::class, 'evangelist_church_id', 'id');
    }
    public function decisions(): HasMany {
        return $this->hasMany(Decision::class);
    }
    public function languages(): BelongsToMany {
        return $this->belongsToMany(Language::class);
    }
    public function districts(): BelongsToMany {
        return $this->belongsToMany(District::class);
    }
    public function postalCodes(): BelongsToMany {
        return $this->belongsToMany(PostalCode::class);
    }
    
    public function pastors(): HasMany {
        return $this->hasMany(User::class)->where('role', 'pastor');
    }
    public function ambassadors(): HasMany {
        return $this->hasMany(User::class)->where('role', 'ambassador');
    }

    public function followUpContact(): BelongsTo {
        return $this->belongsTo(User::class, 'follow_up_contact');
    }
    
    
}
