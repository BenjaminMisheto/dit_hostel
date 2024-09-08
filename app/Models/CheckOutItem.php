<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckOutItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'condition',
        'block_id',
    ];

    // Define the relationship to the Block model
    public function block()
    {
        return $this->belongsTo(Block::class);
    }
}
