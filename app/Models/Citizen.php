<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Citizen extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
    public $timestamps = false;

    public function families() {
        return $this->belongsToMany(Family::class)->withPivot('role');
    }

    public function head_families() {
        return $this->hasMany(Family::class, 'head_citizen_id');
    }
}
