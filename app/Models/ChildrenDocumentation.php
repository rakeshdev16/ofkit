<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildrenDocumentation extends Model
{
    use HasFactory;

    protected $fillable = [
        'therapist_id',
        'children_id',
        'date',
        'start_time',
        'end_time',
        'kindergarten_id',
        'occured',
        'occured_description',
        'group_name',
        'occured_reason',
        'file',
        'type',
        'status',
    ];

    protected $appends = ['file_name'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($document) {
            $document->logActivity('ADD');
        });

        static::updated(function ($document) {
            $document->logActivity('UPDATE');
        });

        static::deleted(function ($document) {
            $document->logActivity('DELETE');
        });
    }

    private function logActivity($type)
    {
        activityLog('ChildrenDocumentation', $this->id, $type);
    }

    public function scopeFilter($query)
    {
        if (request('sort') && request('sorting')) {
            $query->orderBy(request('sort'), request('sorting'));
        }
        if (request('search')) {
            $query->where('type', 'like', '%' . request('search') . '%');
        }
        if (request('date')) {
            $date = explode(',', request('date'));
            $startDate = $date[0] . ' 00:00:00';
            $endDate = $date[1] . ' 23:59:59';
            $query->whereBetween('date', [$startDate, $endDate]);
            // if (strpos(request('date'), ',') !== false) {
            //     $date = explode(',', request('date'));
            //     if (count($date) === 2) {
            //         $dates = array_map(function($date) {
            //             return \DateTime::createFromFormat('d/m/Y', trim($date))->format('Y-m-d');
            //         }, $date);

            //         $startDate = $dates[0] . ' 00:00:00';
            //         $endDate = $dates[1] . ' 23:59:59';

            //         $query->whereBetween('date', [$startDate, $endDate]);
            //     }
            // } else {
            //     $singleDate = \DateTime::createFromFormat('d/m/Y', request('date'))->format('Y-m-d');
            //     $query->whereDate('date', $singleDate);
            // }
        }
        if (request('role')) {
            $userIds = User::where('profession_id', request('role'))->pluck('id')->toArray();
            $query->whereIn('therapist_id', $userIds);
        }
        if (request('therapist_id')) {
            $childirenIds = ChildrenDocumentTherapist::where('therapist_id', request('therapist_id'))->pluck('children_documentation_id')->toArray();
            $query->where('therapist_id', request('therapist_id'))->orWhereIn('id', $childirenIds);
        }
        if (request('type')) {
            $query->where('type', request('type'));
        }
        if (request('status') == 'inactive') {
            $query->whereIn('status', ['active', 'inactive']);
        }else{
            $query->where('status', 'active');
        }
        return $query;
    }

    public function setDateAttribute($value)
    {
        if (is_string($value)) {
            $value = Carbon::createFromFormat('d/m/Y', $value);
            $this->attributes['date'] = $value->format('Y-m-d');
        }
    }

    public function getStartTimeAttribute($value)
    {
        return isset($value) ? date('H:i', strtotime($value)) : '-';
    }

    public function getEndTimeAttribute($value)
    {
        return isset($value) ? date('H:i', strtotime($value)) : '-';
    }

    public function getFileAttribute($value)
    {
        return isset($this->attributes['file']) ? asset('storage/' . $this->attributes['file']) : '';
    }

    public function getFileNameAttribute()
    {
        return isset($this->attributes['file']) ? explode('child-document/', $this->attributes['file'])[1] : '';
    }

    public function groupChildrens()
    {
        return $this->hasMany(GroupChildren::class);
    }

    public function groupTherapist()
    {
        return $this->hasMany(ChildrenDocumentTherapist::class,'children_documentation_id', 'id');
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
        return $this->hasMany(StaffMeeting::class, 'children_doc_id', 'id');
    }

    public function therapist()
    {
        return $this->hasOne(User::class, 'id', 'therapist_id');
    }

    public function staffMeetingChildren()
    {
        return $this->hasMany(StaffMeetingChildren::class, 'children_doc_id', 'id');
    }

    public function staffMeetingTherapist()
    {
        return $this->hasMany(StaffMeetingTherapist::class, 'children_doc_id', 'id');
    }
    public function getFormattedTypeAttribute()
    {
        return ucwords(str_replace('-', ' ', $this->type));
    }
}
