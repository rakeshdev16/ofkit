<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kindergarten extends Model
{
    use HasFactory;

    protected $fillable = ['cluster_id', 'name', 'symbol', 'framework', 'type', 'manager', 'address', 'telephone'];

    public function cluster()
    {
        return $this->hasOne(Cluster::class, 'id', 'cluster_id');
    }
    
    public function kindergartenUser()
    {
        return $this->hasOne(KindergartenUser::class, 'kindergarten_id', 'id');
    }
}
