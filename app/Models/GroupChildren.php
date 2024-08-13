<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupChildren extends Model
{
    use HasFactory;

    protected $fillable = ['children_documentation_id', 'children_id', 'participated', 'reason', 'description', 'file'];
    public function child()
    {
        return $this->belongsTo(Children::class, 'children_id'); // Assuming 'children_id' is the foreign key
    }

}

