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
