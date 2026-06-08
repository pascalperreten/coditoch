<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Decision extends Model
{
    protected $fillable = [
        'event_id',
        'number_of_decisions',
        'evangelist_name',
        'church_id',
    ];

    public function event() {
        return $this->belongsTo(Event::class);
    }
    public function church() {
        return $this->belongsTo(Church::class);
    }
}
