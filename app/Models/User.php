<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
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
        'profession_id',
        'dob',
        'identification',
        'photo',
    ];

    protected $appends = ['date_of_birth'];

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

    public function days()
    {
        return $this->hasMany(StaffSchedule::class);
    }

    public function profession()
    {
        return $this->hasOne(Profession::class, 'id', 'profession_id');
    }
    
    public function staffKindergartens()
    {
        return $this->hasMany(StaffKindergarten::class);
    }

    public function documents()
    {
        return $this->hasMany(StaffDocument::class);
    }

    public function scopeFilter($query)
    {
        if (request('sort') && request('sorting')) {
            $query->orderBy(request('sort'), request('sorting'));
        }
        if (request('kindergarten_id')) {
            $userIds = StaffKindergarten::where('kindergarten_id', request('kindergarten_id'))
                ->where('user_id', '!=', Auth::id())->pluck('user_id')->toArray();
            $query->whereIn('id', $userIds);
        }
        if (request('search')) {
            $query->where('name', 'like', '%'.request('search').'%');
        }
        if (Auth::user()->hasRole(['manager', 'therapist'])) {
            $kindergartenIds = StaffKindergarten::where('user_id', Auth::id())->pluck('kindergarten_id')->toArray();
            $userIds = StaffKindergarten::whereIn('kindergarten_id', $kindergartenIds)->where('user_id', '!=', Auth::id())->pluck('user_id')->toArray();
            $query->whereIn('id', $userIds);
        }
        return $query;
    }

    public function getDateOfBirthAttribute()
    {
        return isset($this->attributes['dob']) ?? @date('d/m/Y', strtotime($this->attributes['dob']));
    }

    public function getPhotoAttribute($value)
    {
        return isset($this->attributes['photo']) ? asset('storage/'.$this->attributes['photo']) : 'https://placehold.co/150x150';
    }

    public function getDocumentAttribute($value)
    {
        return isset($this->attributes['document']) ? asset('storage/'.$this->attributes['document']) : '';
    }
}
