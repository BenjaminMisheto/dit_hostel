<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quantity',
        'semester_id',
        'block_id',
    ];

    // Define the relationship to the Block model
    public function block()
    {
        return $this->belongsTo(Block::class);
    }
        // Relationship with Semester
        public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
