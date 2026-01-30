<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cinema extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = ['name', 'address', 'description'];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
