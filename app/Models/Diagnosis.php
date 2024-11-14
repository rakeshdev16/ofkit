<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status'];

    protected $appends = ['is_assign'];

    public function scopeFilter($query)
    {
        if (request('sort') && request('sorting')) {
            $query->orderBy(request('sort'), request('sorting'));
        }
        if (request('search')) {
            $query->where('name', 'like', '%'.request('search').'%');
        }
        if (request('status')) {
            $query->where('status', request('status'));
        }else{
            $query->where('status', 'active');
        }
        return $query;
    }

    public function getIsAssignAttribute()
    {
        return ChildrenDiagnosis::where('diagnosis_id', @$this->attributes['id'])->exists();
    }
}
