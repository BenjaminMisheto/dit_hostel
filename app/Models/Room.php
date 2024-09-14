<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'floor_id',
        'gender',
        'semester_id',
    ];

    // Define the relationship to the Bed model
    public function beds()
    {
        return $this->hasMany(Bed::class);
    }

    // Define the relationship to the Floor model
    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    // Define the relationship to the User model
    public function users()
    {
        return $this->hasMany(User::class);
    }
        // Relationship with Semester
        public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
