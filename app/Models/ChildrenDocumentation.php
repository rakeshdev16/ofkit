<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Session;

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
        Session::forget(['doc_sort', 'doc_sorting']);
        if (request('sort') && request('sorting')) {
            Session::put('doc_sort', request('sort'));
            Session::put('doc_sorting', request('sorting'));
            $query->orderBy(request('sort'), request('sorting'));
        }
        Session::forget('doc_search');
        if (request('search')) {
            Session::put('doc_search', request('search'));
            $query->where('type', 'like', '%' . request('search') . '%');
        }
        Session::forget('doc_date');
        if (request('date')) {
            Session::put('doc_date', request('date'));
            $date = explode(',', request('date'));
            $startDate = $date[0] . ' 00:00:00';
            $endDate = $date[1] . ' 23:59:59';
            $query->whereBetween('date', [$startDate, $endDate]);
        }
        Session::forget('doc_role');
        if (request('role')) {
            Session::put('doc_role', request('role'));
            $userIds = User::where('profession_id', request('role'))->pluck('id')->toArray();
            $query->whereIn('therapist_id', $userIds);
        }
        Session::forget('doc_therapist_id');
        if (request('therapist_id')) {
            Session::put('doc_therapist_id', request('therapist_id'));
            $query->where('therapist_id', request('therapist_id'));
        }
        Session::forget('doc_type');
        if (request('type')) {
            Session::put('doc_type', request('type'));
            $query->where('type', request('type'));
            if (request('type') == 'group' && !empty(request('therapist_id'))) {
                $childirenIds = ChildrenDocumentTherapist::where('therapist_id', request('therapist_id'))->pluck('children_documentation_id')->toArray();
                $query->orWhereIn('id', $childirenIds);
            }
        }
        Session::forget('doc_status');
        if (request('status') == 'inactive') {
            Session::put('doc_status', request('status'));
            $query->whereIn('status', ['active', 'inactive']);
        }else{
            Session::put('doc_status', request('status'));
            $query->where('status', 'active');
        }
        return $query;
    }

    public function getDateAttribute()
    {
        return isset($this->attributes['date']) ? date('d/m/Y', strtotime($this->attributes['date'])) : NULL;
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
