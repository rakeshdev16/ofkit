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
        'father_email',
        'father_telephone',
        'father_work',
        'mother_name',
        'mother_email',
        'mother_telephone',
        'mother_work',
        'family_status',
        'siblings',
        'disabilities',
        'name',
        'relationship',
        'telephone',
    ];

    public function language()
    {
        return $this->hasMany(FamilyLanguage::class);
    }
}
