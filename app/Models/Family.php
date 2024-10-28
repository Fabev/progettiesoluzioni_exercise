<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function citizens() {
        return $this->belongsToMany(Citizen::class)->withPivot('role');
    }

    public function head_citizen() {
        return $this->belongsTo(Citizen::class, 'head_citizen_id');
    }
}
