<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', // e.g., '2023/2024'
        'start_date',
        'end_date',
    ];



    // Define relationships if necessary
    public function adminCheckouts()
    {
        return $this->hasMany(AdminCheckout::class);
    }

    public function beds()
    {
        return $this->hasMany(Bed::class);
    }

    public function blocks()
    {
        return $this->hasMany(Block::class);
    }

    public function checkOutItems()
    {
        return $this->hasMany(CheckOutItem::class);
    }

    public function eligibleStudents()
    {
        return $this->hasMany(EligibleStudent::class);
    }

    public function floors()
    {
        return $this->hasMany(Floor::class);
    }

    public function publishes()
    {
        return $this->hasMany(Publish::class);
    }

    public function requirements()
    {
        return $this->hasMany(Requirement::class);
    }

    public function requirementItemConfirmations()
    {
        return $this->hasMany(RequirementItemConfirmation::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function sliderData()
    {
        return $this->hasMany(SliderData::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
