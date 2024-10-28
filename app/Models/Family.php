<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function citizens() {
        return $this->belongsToMany(Citizen::class)->withPivot(['role', 'is_head']);
    }

    public function head_citizen() {
        return $this->citizens()->wherePivot('is_head',true)->first();
    }

    /**
     * Promote a citizen as head of the family
     *
     * @param Citizen $citizen
     * @return bool
     */
    public function promoteAsHead(Citizen $citizen) : bool {
        $this->head_citizen()->pivot->update(['is_head' => false]);
        return $citizen->pivot->update(['is_head' => true]);
    }
}
