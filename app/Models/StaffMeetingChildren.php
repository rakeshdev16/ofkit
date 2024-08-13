<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffMeetingChildren extends Model
{
    use HasFactory;

    protected $fillable = ['children_doc_id', 'children_id'];
    public function child()
    {
        return $this->belongsTo(Children::class, 'children_id');
    }

    protected $appends = ['children'];

    public function getChildrenAttribute()
    {
        return Children::where('id', $this->attributes['children_id'])->pluck('name')->first();
    }

}
