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
            $query->where('cluster', 'like', '%'.request('search').'%');
        }
        return $query;
    }

    public function manager()
    {
        return $this->hasOne(User::class, 'id', 'manager_id');
    }

    public function kindergartens()
    {
        return $this->hasMany(ClusterKindergarten::class);
    }
}
