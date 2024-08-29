<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElligableStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_name',
        'registration_number',
        'payment_status',
        'sponsorship',
        'phone',
        'gender',
        'nationality',
        'course',
    ];
}
