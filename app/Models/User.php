<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'telephone',
        'licence_number',
        'profession',
        'dob',
        'identification',
        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function userKindergarten()
    {
        return $this->hasOne(KindergartenUser::class, 'user_id', 'id');
    }
    
    public function kindergarten()
    {
        return $this->hasOne(Kindergarten::class, 'id', 'kindergarten_id');
    }

    public function days()
    {
        return $this->hasMany(StaffSchedule::class);
    }

    public function getDobAttribute($value)
    {
        return $value ? date('d M Y', strtotime($this->attributes['dob'])) : '-';
    }

    public function getPhotoAttribute($value)
    {
        return asset('storage/'.$this->attributes['photo']);
    }
}
