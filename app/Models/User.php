<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'registration_number',
        'Control_Number',
        'status',
        'sponsorship',
        'phone',
        'gender',
        'nationality',
        'course',
        'block_id',
        'floor_id',
        'room_id',
        'bed_id',
        'email',
        'password',
        'profile_photo_path',
        'confirmation',
        'application',
        'counter',
        'checkin',
        'checkout',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'expiration_date' => 'datetime',
        'application' => 'boolean',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    // Define the inverse of the one-to-one relationship with the Bed model
    public function bed()
    {
        return $this->belongsTo(Bed::class, 'bed_id');
    }

    // Define the inverse relationships if needed
    public function block()
    {
        return $this->belongsTo(Block::class, 'block_id');
    }

    public function floor()
    {
        return $this->belongsTo(Floor::class, 'floor_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}

