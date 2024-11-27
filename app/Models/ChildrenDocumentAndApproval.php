<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildrenDocumentAndApproval extends Model
{
    use HasFactory;

    protected $fillable = ['children_id', 'document', 'file_type_id', 'description', 'user_id'];

    protected $appends = ['file_type'];

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
        activityLog('ChildrenDocumentAndApproval', $this->id, $type);
    }

    public function scopeFilter($query)
    {
        if (request('sort') && request('sorting')) {
            if(request('sort') == 'therapist') {
                $query->leftJoin('users', 'users.id', '=', 'children_document_and_approvals.user_id')
                ->orderBy('users.name', request('sorting'))
                ->select('children_document_and_approvals.*');
            }else {
                $query->orderBy(request('sort'), request('sorting'));
            }
        }
        if (request('search')) {
            $query->where('document', 'like', '%'.request('search').'%');
        }
        if (request('file_type_id')) {
            $query->where('file_type_id', request('file_type_id'));
        }
        if (request('date')) {
            $date = explode(',', request('date'));
            $startDate = $date[0] . ' 00:00:00';
            $endDate = $date[1] . ' 23:59:59';
            $query->whereBetween('created_at', [$startDate, $endDate]);
            // if (strpos(request('date'), ',') !== false) {
            //     $date = explode(',', request('date'));
            //     if (count($date) === 2) {
            //         $dates = array_map(function($date) {
            //             return \DateTime::createFromFormat('d/m/Y', trim($date))->format('Y-m-d');
            //         }, $date);

            //         $startDate = $dates[0] . ' 00:00:00';
            //         $endDate = $dates[1] . ' 23:59:59';

            //         $query->whereBetween('created_at', [$startDate, $endDate]);
            //     }
            // } else {
            //     $singleDate = \DateTime::createFromFormat('d/m/Y', request('date'))->format('Y-m-d');
            //     $query->whereDate('created_at', $singleDate);
            // }
        }
        // if (request('status')) {
        //     $query->where('status', request('status'));
        // }else{
        //     $query->where('status', 'active');
        // }
         if (request('status')) {
            $query->where('children_document_and_approvals.status', request('status'));
        } else {
            $query->where('children_document_and_approvals.status', 'active');
        }

        return $query;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getFileTypeAttribute($value)
    {
        return FileType::where('id', $this->attributes['file_type_id'])->pluck('name')->first();
    }

    public function getDocumentAttribute($value)
    {
        return isset($this->attributes['document']) ? asset('storage/'.$this->attributes['document']) : NULL;
    }
}
