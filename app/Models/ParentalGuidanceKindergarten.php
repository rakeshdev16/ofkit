<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentalGuidanceKindergarten extends Model
{
    use HasFactory;

    protected $fillable = ['children_doc_id', 'kindergarten_id'];
}
