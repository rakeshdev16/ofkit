<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    use HasFactory;

    protected $fillable = ['manager_id', 'cluster'];

    public function manager()
    {
        return $this->hasOne(User::class, 'id', 'manager_id');
    }
}
