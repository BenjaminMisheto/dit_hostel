<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    // Fillable attributes for mass assignment
    protected $fillable = [
        'name', // e.g., '2023/2024'
        'start_date',
        'end_date',
        'is_closed',
    ];

    // Define relationships
    public function adminCheckouts()
    {
        return $this->hasMany(AdminCheckout::class);
    }


    public function requirementItemConfirmations()
    {
        return $this->hasMany(RequirementItemConfirmation::class);
    }


    public function users()
    {
        return $this->hasMany(User::class);
    }
}
