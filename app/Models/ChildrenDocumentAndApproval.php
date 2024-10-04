<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildrenDocumentAndApproval extends Model
{
    use HasFactory;

    protected $fillable = ['children_id', 'document', 'file_type_id', 'description'];

    protected $appends = ['file_type'];

    public function scopeFilter($query)
    {
        if (request('sort') && request('sorting')) {
            $query->orderBy(request('sort'), request('sorting'));
        }
        if (request('search')) {
            $query->where('document', 'like', '%'.request('search').'%');
        }
        if (request('file_type_id')) {
            $query->where('file_type_id', request('file_type_id'));
        }
        if (request('date')) {
            if (strpos(request('date'), ',') !== false) {
                $date = explode(',', request('date'));
                $query->whereBetween('created_at', $date);
            } else {
                $query->whereDate('created_at', request('date'));
            }
        }
        return $query;
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
