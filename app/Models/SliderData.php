<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SliderData extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'slider_data';

    // Define the fillable attributes
    protected $fillable = [
        'block_id',
        'floor_id',
        'criteria',
        'value',
        'bed_id',
        'status',
    ];

    // Define the relationships if necessary
    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }


}
