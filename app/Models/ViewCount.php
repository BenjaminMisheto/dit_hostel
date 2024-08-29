<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ViewCount extends Model
{
    use HasFactory;

    // Specify the table associated with the model
    protected $table = 'view_counts';

    // The attributes that are mass assignable
    protected $fillable = [
        'total_views',
        'monthly_views',
        'views_january',
        'views_february',
        'views_march',
        'views_april',
        'views_may',
        'views_june',
        'views_july',
        'views_august',
        'views_september',
        'views_october',
        'views_november',
        'views_december'
    ];

    // The attributes that should be cast to native types
    protected $casts = [
        'total_views' => 'integer',
        'monthly_views' => 'integer',
        'views_january' => 'integer',
        'views_february' => 'integer',
        'views_march' => 'integer',
        'views_april' => 'integer',
        'views_may' => 'integer',
        'views_june' => 'integer',
        'views_july' => 'integer',
        'views_august' => 'integer',
        'views_september' => 'integer',
        'views_october' => 'integer',
        'views_november' => 'integer',
        'views_december' => 'integer'
    ];
}
