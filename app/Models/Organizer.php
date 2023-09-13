<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizer extends Model
{
    use HasFactory;

    protected $fillable = ['organizerName', 'imageLocation'];

    /**
     * Define the relationship between Organizer and SportEvent.
     */
    public function sportEvents()
    {
        return $this->hasMany(SportEvent::class);
    }
}
