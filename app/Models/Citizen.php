<?php

namespace App\Models;

use App\Enumerations\FamilyRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Citizen extends Model
{
    use SoftDeletes, HasFactory;

    protected $guarded = ['id'];
    public $timestamps = false;

    public function families() {
        return $this->belongsToMany(Family::class)->withPivot(['role', 'is_head']);
    }

    public function head_families() {
        return $this->families()->wherePivot('is_head', true)->get();
    }

    /**
     * Count number of families where this citizen is the head
     * and has a parent role
     *
     * @return int
     */
    public function countHeadAndParentFamilies() : int {
        return $this->families()
            ->wherePivot('is_head', true)
            ->wherePivot('role', FamilyRoles::PARENT->value)
            ->count();
    }
}
