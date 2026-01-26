<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'title', 'description', 'duration', 'release_date', 
        'poster', 'trailer_url', 'status'
    ];

    protected $casts = [
        'release_date' => 'date',
    ];

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }
}
