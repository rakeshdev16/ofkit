<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    use HasFactory;

    protected $fillable = ['manager_id', 'cluster'];

    public function scopeFilter($query)
    {
        if (request('sort') && request('sorting')) {
            $query->orderBy(request('sort'), request('sorting'));
        }
        if (request('search')) {
            $memberId = User::where('name', 'like', '%'.request('search').'%')->pluck('id')->toArray();
            $query->whereIn('manager_id', $memberId);
        }
        return $query;
    }

    public function manager()
    {
        return $this->hasOne(User::class, 'id', 'manager_id');
    }
}
