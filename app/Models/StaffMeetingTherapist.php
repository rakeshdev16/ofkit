<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffMeetingTherapist extends Model
{
    use HasFactory;

    protected $fillable = ['children_doc_id', 'therapist_id'];
    public function therapist()
    {
        return $this->belongsTo(User::class, 'therapist_id');
    }

}
