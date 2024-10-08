<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequirementItemConfirmation extends Model
{
    use HasFactory;

    // Define the table if it differs from the model name
    protected $table = 'requirement_item_confirmations';

    // Fillable attributes for mass assignment
    protected $fillable = [
        'user_id',
        'items_to_bring_names',
        'checkout_items_names',
        'semester_id',
        'gender',
    ];

    // Cast attributes to a specific type
    protected $casts = [
        'items_to_bring_names' => 'array',
        'checkout_items_names' => 'array',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Semester
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
