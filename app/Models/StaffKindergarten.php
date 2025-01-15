<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffKindergarten extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'kindergarten_id', 'role_id', 'association_id'];

    public function kindergartens()
    {
        return $this->hasOne(Kindergarten::class, 'id', 'kindergarten_id');
    }

    public function association()
    {
        return $this->hasOne(Association::class, 'id', 'association_id');
    }

    public function profession()
    {
        return $this->hasOne(Profession::class, 'id', 'role_id');
    }
}
