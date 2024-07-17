<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildrenMedicalInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'children_id',
        'food_allergie',
        'food_allergie_detail',
        'medicine',
        'medicine_detail',
        'medicine_name',
        'dosage_and_timing',
    ];
}
