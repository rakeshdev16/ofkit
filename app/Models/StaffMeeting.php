<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffMeeting extends Model
{
    use HasFactory;

    protected $fillable = ['children_doc_id', 'children_id', 'topic', 'discussion', 'decisions'];

}
