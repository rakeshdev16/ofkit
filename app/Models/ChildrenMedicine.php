<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildrenMedicine extends Model
{
    use HasFactory;

    protected $fillable = ['children_id', 'name', 'type', 'dosage_and_timing', 'where'];
}
