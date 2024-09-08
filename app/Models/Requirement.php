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
        'block_id',
    ];

    // Define the relationship to the Block model
    public function block()
    {
        return $this->belongsTo(Block::class);
    }
}
