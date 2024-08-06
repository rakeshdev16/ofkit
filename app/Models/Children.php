<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Children extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kindergarten_id',
        'name',
        'family_name',
        'identification',
        'gender',
        'dob',
        'age',
        'address',
        'functionality_id',
        'diagnosis_id',
        'status_id',
        'service_start_date',
        'hmo_id',
        'photo',
    ];

    protected $appends = ['date_of_birth', 'profile'];

    public function scopeFilter($query)
    {
        if (request('sort') && request('sorting')) {
            $query->orderBy(request('sort'), request('sorting'));
        }
        if (request('search')) {
            $search = request('search');
            $query->where('name', 'like', '%'.$search.'%')->orWhere('family_name', 'like', '%'.$search.'%')
                ->orWhereIn('kindergarten_id', Kindergarten::where('name', 'like', '%'.$search.'%')->pluck('id'));
        }

        if (Auth::user()->hasRole(['manager', 'therapist'])) {
            $kindergartenIds = StaffKindergarten::where('user_id', Auth::id())->pluck('kindergarten_id')->toArray();
            $query->whereIn('kindergarten_id', $kindergartenIds);
        }
        return $query;
    }

    public function getDateOfBirthAttribute()
    {
        return date('d/m/Y', strtotime($this->attributes['dob']));
    }

    public function getProfileAttribute($value)
    {
        return isset($this->attributes['photo']) ? asset('storage/'.$this->attributes['photo']) : asset('assets/images/avatars/dummy-image.webp');
    }

    public function parent()
    {
        return $this->hasOne(ChildrenParent::class);
    }

    public function medicalInformation()
    {
        return $this->hasOne(ChildrenMedicalInformation::class);
    }

    public function medicine()
    {
        return $this->hasMany(ChildrenMedicine::class);
    }

    public function diagnosis()
    {
        return $this->hasMany(ChildrenDiagnosis::class);
    }

    public function language()
    {
        return $this->hasMany(FamilyLanguage::class);
    }
}
