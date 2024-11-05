<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffDocument extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'description'];

    protected $appends = ['file_name'];

    public function getNameAttribute($value)
    {
        return isset($this->attributes['name']) ? asset('storage/'.$this->attributes['name']) : '';
    }
    
    public function getFileNameAttribute($value)
    {
        return @explode('staff-document/', $this->attributes['name'])[1];
    }
}
