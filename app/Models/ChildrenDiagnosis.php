<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildrenDiagnosis extends Model
{
    use HasFactory;

    protected $fillable = ['children_id', 'diagnosis_id'];
}
