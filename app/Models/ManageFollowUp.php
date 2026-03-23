<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManageFollowUp extends Model
{
    protected $fillable = [
        'event_id',
        'language', 
        'age',
        'gender',
        'location',
        'evangelist_name',
        'church_evangelize',
        'assign_directly',
    ];

    protected $casts = [
        'language' => 'boolean',
        'age' => 'boolean',
        'gender' => 'boolean',
        'location' => 'boolean',
        'evangelist_name' => 'boolean',
        'church_evangelize' => 'boolean',
        'assign_directly' => 'boolean',

    ];
    
    public function event(): BelongsTo {
        return $this->belongsTo(Event::class);
    }
}
