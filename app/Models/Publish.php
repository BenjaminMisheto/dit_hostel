<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publish extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'algorithm',
        'reserved_bed',
        'maintenance_bed',
        'expiration_date',
        'semester_id',
        'deadline',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',

    ];

        // Relationship with Semester
        public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
