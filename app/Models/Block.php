<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'manager',
        'number_of_floors',
        'price',
        'location',
        'image_data',
    ];

    // Define the relationship to the Floor model
    public function floors()
    {
        return $this->hasMany(Floor::class);
    }

    // Define the relationship to the User model
    public function users()
    {
        return $this->hasMany(User::class);
    }

// Block.php
public function sliderData()
{
    return $this->hasMany(SliderData::class);
}
    // Relationship with Semester
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

}
