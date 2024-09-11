<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckOutItem extends Model
{
    use HasFactory;

    // Define which attributes are mass assignable
    protected $fillable = [
        'name',
        'condition',
        'block_id',
        'floor_id', // Added floor_id
        'room_id'   // Added room_id
    ];

    // Define the relationship to the Block model
    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    // Define the relationship to the Floor model
    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    // Define the relationship to the Room model
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
