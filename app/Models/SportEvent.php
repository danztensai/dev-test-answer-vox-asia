<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SportEvent extends Model
{
    use HasFactory;
    protected $fillable = ['eventDate', 'eventName', 'eventType', 'organizer_id'];

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }
}
