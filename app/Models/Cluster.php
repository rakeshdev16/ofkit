<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    use HasFactory;

    protected $fillable = ['manager_id', 'cluster', 'status'];

    protected $appends = ['is_assign'];


    public function scopeFilter($query)
    {
        if (request('sort') && request('sorting')) {
            $query->orderBy(request('sort'), request('sorting'));
        }else{
            $query->orderBy('cluster', 'ASC');
        }
        if (request('search')) {
            $search = request('search');
            $clusterIds = ClusterKindergarten::whereIn('kindergarten_id', Kindergarten::where('name', 'like', '%'.$search.'%')->pluck('id'))->pluck('cluster_id');
            $query->where('cluster', 'like', '%'.$search.'%')->orWhereIn('id', $clusterIds);
        }
        if (request('status') == 'inactive') {
            $query->whereIn('status', ['active', 'inactive']);
        }else{
            $query->where('status', 'active');
        }
        return $query;
    }

    public function getIsAssignAttribute()
    {
        return Kindergarten::where('cluster_id', @$this->attributes['id'])->where('status', 'active')->exists();
    }

    public function manager()
    {
        return $this->hasOne(User::class, 'id', 'manager_id');
    }

    public function kindergartens()
    {
        return $this->hasMany(Kindergarten::class, 'cluster_id', 'id');
    }
}
