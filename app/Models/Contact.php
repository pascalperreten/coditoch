<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use App\Models\Church;
use App\Models\District;
use App\Models\Language;
use App\Models\PostalCode;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Contact extends Model
{

    protected $fillable = [
        'name',
        'gender',
        'age',
        'way_to_get_in_contact',
        'social_media',
        'email',
        'phone',
        'other_contact',
        'foreign_city',
        'city',
        'comments',
        'contacted_date',
        'invalid_contact_details',
        'meeting_date',
        'met',
        'not_interested',
        'not_reached',
        'part_of_church',
        'decision',
        'assigned',
        'evangelist_name',
        'event_id',
        'church_id',
        'church_name',
        'evangelist_church_id',
        'follow_up_person',
    ];

    public function casts(): array {
        return [
            'foreign_city' => 'boolean',
            'decision' => 'boolean',
            'not_interested' => 'boolean',
            'invalid_contact_details' => 'boolean',
            'contact_information' => 'array',
            'assigned' => 'boolean',
            'social_media' => 'array',
            'contacted_date' => 'date',
            'meeting_date' => 'date',
            'met' => 'boolean',
            'part_of_church' => 'boolean',
        ];
    }

    public function event(): BelongsTo {
        return $this->belongsTo(Event::class);
    }

    public function church(): BelongsTo {
        return $this->belongsTo(Church::class);
    }

    public function district(): BelongsToMany {
        return $this->belongsToMany(District::class);
    }
    public function postalCode(): BelongsToMany {
        return $this->belongsToMany(PostalCode::class);
    }
    public function languages(): BelongsToMany {
        return $this->belongsToMany(Language::class);
    }

    public function churchOfEvangelist(): BelongsTo {
        return $this->belongsTo(Church::class, 'evangelist_church_id', 'id');
    }

    public function followUpPerson(): BelongsTo {
        return $this->belongsTo(User::class, 'follow_up_person', 'id');
    }
}
