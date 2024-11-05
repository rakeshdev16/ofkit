<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildrenDocumentTherapist extends Model
{
    use HasFactory;

    protected $fillable = ['children_documentation_id', 'therapist_id'];
}
