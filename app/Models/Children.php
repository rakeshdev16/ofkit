<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected $appends = ['date_of_birth'];

    public function scopeFilter($query)
    {
        if (request('sort') && request('sorting')) {
            $query->orderBy(request('sort'), request('sorting'));
        }
        if (request('search')) {
            $search = request('search');
            $query->where('name', 'like', '%'.$search.'%')
                ->orWhereIn('kindergarten_id', Kindergarten::where('name', 'like', '%'.$search.'%')->pluck('id'));
        }
        return $query;
    }

    public function getDateOfBirthAttribute()
    {
        return date('d/m/Y', strtotime($this->attributes['dob']));
    }

    public function getPhotoAttribute($value)
    {
        return isset($this->attributes['photo']) ? asset('storage/'.$this->attributes['photo']) : 'https://placehold.co/150x150';
    }

    public function parent()
    {
        return $this->hasOne(ChildrenParent::class);
    }

    public function medicalInformation()
    {
        return $this->hasOne(ChildrenMedicalInformation::class);
    }
}
