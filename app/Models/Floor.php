<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use HasFactory;

    protected $fillable = [
        'floor_number',
        'number_of_rooms',
        'gender',
        'eligibility',
        'block_id',
    ];
    protected $casts = [
        'gender' => 'array',  // Ensure 'gender' is cast to an array
        'eligibility' => 'array',
    ];

    // Define the relationship to the Room model
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    // Define the relationship to the Block model
    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    // Define the relationship to the User model
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function sliderData()
{
    return $this->hasMany(SliderData::class);
}
}
