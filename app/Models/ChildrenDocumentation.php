<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildrenDocumentation extends Model
{
    use HasFactory;

    protected $fillable = ['children_id', 'date', 'start_time', 'end_time', 'kindergarten_id', 'occured', 'occured_description', 'occured_reason', 'file', 'type'];

    public function scopeFilter($query)
    {
        if (request('sort') && request('sorting')) {
            $query->orderBy(request('sort'), request('sorting'));
        }
        if (request('search')) {
            $query->where('cluster', 'like', '%'.request('search').'%');
        }
        return $query;
    }

    public function getFileAttribute($value)
    {
        return isset($this->attributes['file']) ? asset('storage/'.$this->attributes['file']) : '';
    }

    public function groupChildrens()
    {
        return $this->hasMany(GroupChildren::class);
    }
    
    public function parentalGuidanceChildren()
    {
        return $this->hasMany(ParentalGuidanceChildren::class, 'children_doc_id', 'id');
    }
    
    public function parentalGuidanceKindergarten()
    {
        return $this->hasMany(ParentalGuidanceKindergarten::class, 'children_doc_id', 'id');
    }
    
    public function staffMeeting()
    {
        return $this->hasOne(StaffMeeting::class, 'children_doc_id', 'id');
    }
    
    public function staffMeetingChildren()
    {
        return $this->hasMany(StaffMeetingChildren::class, 'children_doc_id', 'id');
    }
    
    public function staffMeetingTherapist()
    {
        return $this->hasMany(StaffMeetingTherapist::class, 'children_doc_id', 'id');
    }
}
