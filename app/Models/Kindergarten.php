<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kindergarten extends Model
{
    use HasFactory;

    protected $fillable = ['cluster_id', 'name', 'symbol', 'framework_type_id', 'kindergarten_type_id', 'address', 'telephone', 'status'];

    protected $appends = ['framework_type', 'kindergarten_type', 'is_assign'];

    public function scopeFilter($query)
    {
        if (request('sort') && request('sorting')) {
            if(request('sort') == 'cluster_id') {
                $query->leftJoin('clusters', 'clusters.id', '=', 'kindergartens.cluster_id')
                    ->orderBy('clusters.cluster', request('sorting'))
                    ->select('kindergartens.*');
            }elseif(request('sort') == 'cluster_manager_id') {
                $query->leftJoin('clusters', 'clusters.id', '=', 'kindergartens.cluster_id')
                    ->leftJoin('users', 'users.id', '=', 'clusters.manager_id')
                    ->orderBy('users.name', request('sorting'))
                    ->select('kindergartens.*');
            }elseif(request('sort') == 'kindergarten_manager_id') {
                $query->leftJoin('kindergarten_users', 'kindergarten_users.kindergarten_id', '=', 'kindergartens.id')
                    ->leftJoin('users', 'users.id', '=', 'kindergarten_users.user_id')
                    ->orderBy('users.name', request('sorting'))
                    ->select('kindergartens.*');
            }else {
                $query->orderBy(request('sort'), request('sorting'));
            }
        }else{
            $query->orderBy('name', 'ASC');
        }
        if (request('search')) {
            $query->where('name', 'like', '%'.request('search').'%');
        }
        // if (request('status')) {
        //     $query->where('status', request('status'));
        // }else{
        //     $query->where('status', 'active');
        // }
        if (request('status') == 'inactive') {
            $query->whereIn('kindergartens.status', ['active', 'inactive']);
        } else {
            $query->where('kindergartens.status', 'active');
        }
        return $query;
    }

    public function getIsAssignAttribute()
    {
        return Children::where('kindergarten_id', @$this->attributes['id'])->exists() || StaffKindergarten::where('kindergarten_id', @$this->attributes['id'])->exists();
    }

    public function getFrameworkTypeAttribute()
    {
        return FrameworkType::where('id', @$this->attributes['framework_type_id'])->pluck('name')->first();
    }

    public function getKindergartenTypeAttribute()
    {
        return KindergartenType::where('id', @$this->attributes['kindergarten_type_id'])->pluck('name')->first();
    }

    public function cluster()
    {
        return $this->hasOne(Cluster::class, 'id', 'cluster_id');
    }

    public function kindergartenUser()
    {
        return $this->hasOne(KindergartenUser::class, 'kindergarten_id', 'id');
    }
}
