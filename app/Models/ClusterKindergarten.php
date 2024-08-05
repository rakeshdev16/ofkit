<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClusterKindergarten extends Model
{
    use HasFactory;

    protected $fillable = ['cluster_id', 'kindergarten_id'];
}
