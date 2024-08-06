<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyLanguage extends Model
{
    use HasFactory;

    protected $fillable = ['children_parent_id', 'language'];
}
