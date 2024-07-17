<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildrenParent extends Model
{
    use HasFactory;

    protected $fillable = [
        'children_id',
        'father_name',
        'father_telephone',
        'mother_name',
        'mother_telephone',
        'family_status',
        'name',
        'relationship',
        'telephone',
    ];
}
