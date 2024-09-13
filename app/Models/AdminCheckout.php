<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminCheckout extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'condition',
    ];

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
