<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    use HasFactory;

    protected $fillable = [
        'bed_number',
        'room_id',
        'user_id',
        'status',
    ];

    // Define the relationship to the Room model
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // Define the relationship to the Floor model (through Room)
    public function floor()
    {
        return $this->room->floor();
    }

    // Define the relationship to the Block model (through Room -> Floor)
    public function block()
    {
        return $this->room->floor->block();
    }

    // Define the one-to-one relationship with the User model
    public function user()
    {
        return $this->hasOne(User::class, 'bed_id');
    }
        // Relationship with Semester
        public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
