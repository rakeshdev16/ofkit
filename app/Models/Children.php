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
            if (request('sort') == 'kindergarten_id') {
                if (Auth::user()->hasRole(['manager', 'therapist'])) {
                    $kindergartenIds = StaffKindergarten::where('user_id', Auth::id())->pluck('kindergarten_id')->toArray();
                } else {
                    $kindergartenIds = Kindergarten::pluck('id')->toArray();
                }
                $query->join('kindergartens', 'childrens.kindergarten_id', '=', 'kindergartens.id')
                    ->whereIn('kindergartens.id', $kindergartenIds)
                    ->orderBy('kindergartens.name', request('sorting'));
            } else {
                $query->orderBy(request('sort'), request('sorting'));
            }
        } else {
            $query->orderBy('childrens.id', 'DESC');
        }

        if (request('kindergarten_id')) {
            $query->where('childrens.kindergarten_id', request('kindergarten_id'));
        }

        if (request('search')) {
            $search = request('search');

            $query->where(function ($query) use ($search) {
                $query->where('childrens.name', 'like', '%' . $search . '%')
                    ->orWhere('childrens.family_name', 'like', '%' . $search . '%')
                    ->orWhere('childrens.identification', 'like', '%' . $search . '%')
                    ->orWhere('childrens.address', 'like', '%' . $search . '%');
            });
        }

        if (Auth::user()->hasRole(['manager', 'therapist'])) {
            $kindergartenIds = StaffKindergarten::where('user_id', Auth::id())->pluck('kindergarten_id')->toArray();
            $query->whereIn('childrens.kindergarten_id', $kindergartenIds);
        }

        return $query;
    }


    public function kindergarten()
    {
        return $this->belongsTo(Kindergarten::class);
    }

    public function getDateOfBirthAttribute()
    {
        return date('d/m/Y', strtotime($this->attributes['dob']));
    }

    public function getProfileAttribute($value)
    {
        return isset($this->attributes['photo']) ? asset('storage/'.$this->attributes['photo']) : asset('assets/images/avatars/dummy-image.webp');
    }
    public function documentation()
    {
        return $this->hasMany(ChildrenDocumentation::class, 'children_id');
    }
    public function staffMeetingChildren()
    {
        return $this->hasMany(StaffMeetingChildren::class, 'children_id');
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
