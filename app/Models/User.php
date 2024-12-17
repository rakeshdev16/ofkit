<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
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
        'first_name',
        'family_name',
        'email',
        'password',
        'address',
        'telephone',
        'licence_number',
        'profession_id',
        'dob',
        'identification',
        'photo',
        'otp',
        'otp_expires_at',
        'last_activity_at',
        'status',
    ];

    protected $appends = ['date_of_birth', 'profile', 'is_assign'];

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
        'otp_expires_at' => 'datetime',
        // 'last_activity_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPassword($token));
    }


    public function getIsAssignAttribute()
    {
        return KindergartenUser::where('user_id', @$this->attributes['id'])->exists() || Cluster::where('manager_id', @$this->attributes['id'])->exists();
    }

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
        if (Auth::user()->hasRole(['manager', 'therapist'])) {
            $kindergartenIds = StaffKindergarten::where('user_id', Auth::id())->pluck('kindergarten_id')->toArray();
            $userIds = StaffKindergarten::whereIn('kindergarten_id', $kindergartenIds)->where('user_id', '!=', Auth::id())->pluck('user_id')->toArray();
            $userIds[] = Auth::id();
            $query->whereIn('id', $userIds);
        }
        if (request('sort') && request('sorting')) {
            Session::put('staff_sorting', [
                'key' => request('sort'),
                'value' => request('sorting'),
            ]);
            if(request('sort') == 'profession_id') {
                $query->leftJoin('professions', 'professions.id', '=', 'users.profession_id')
                    ->orderBy('professions.name', request('sorting'))
                    ->select('users.*');
            }else{
                $query->orderBy(request('sort'), request('sorting'));
            }
        }else{
            $query->orderBy('name', 'ASC');
        }
        if (request('kindergarten_id')) {
            Session::put('staff_kindergarten', request('kindergarten_id'));
            $userIds = StaffKindergarten::whereIn('kindergarten_id', explode(',',request('kindergarten_id')))
                ->where('user_id', '!=', Auth::id())->pluck('user_id')->toArray();
            $query->whereIn('id', $userIds);
        }else{
            Session::forget('staff_kindergarten');
        }
        if (request('kindergarten_id')) {
            Session::put('staff_kindergarten', request('kindergarten_id'));
            $userIds = StaffKindergarten::whereIn('kindergarten_id', explode(',',request('kindergarten_id')))
                ->where('user_id', '!=', Auth::id())->pluck('user_id')->toArray();
            $query->whereIn('users.id', $userIds);
        }else{
            Session::forget('staff_kindergarten');
        }
        if (request('profession_id')) {
            Session::put('staff_profession', request('profession_id'));
            $query->where('profession_id', request('profession_id'));
        }else{
            Session::forget('staff_profession');
        }
        if (request('status') == 'inactive') {
            $query->whereIn('users.status', ['active', 'inactive']);
        }else{
            $query->where('users.status', 'active');
        }
        if (request('search')) {
            $query->whereNot('id', Auth::id())->where('name', 'like', '%'.request('search').'%')->orWhere('email', 'like', '%'.request('search').'%');
        }
        if (Auth::user()->hasRole('admin')) {
            $query->whereNot('users.id', Auth::id());
        }
        return $query;
    }

    public function getDateOfBirthAttribute()
    {
        return isset($this->attributes['dob']) ? @date('d/m/Y', strtotime($this->attributes['dob'])) : NULL;
    }

    public function getProfileAttribute($value)
    {
        return isset($this->attributes['photo']) ? asset('storage/'.$this->attributes['photo']) : asset('assets/images/avatars/dummy-image.webp');
    }

    public function getDocumentAttribute($value)
    {
        return isset($this->attributes['document']) ? asset('storage/'.$this->attributes['document']) : '';
    }
    public function staffMeetingTherapists()
    {
        return $this->hasMany(StaffMeetingTherapist::class, 'therapist_id');
    }
}
