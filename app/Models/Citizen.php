<?php

namespace App\Models;

use App\Enumerations\FamilyRoles;
use App\Exceptions\CannotLeaveFamily;
use App\Exceptions\CannotLeaveAFamilyAsHead;
use App\Exceptions\CannotPromoteAChildToHeadException;
use App\Exceptions\CitizenIsNotPartOfTheFamilyException;
use App\Exceptions\FamilyHasMoreThanSixMembersException;
use App\Exceptions\MaximumFamiliesHeadException;
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

    /**
     * Join a family
     *
     * @param Family $family
     * @param FamilyRoles|string $role
     * @param bool $isHead
     * @return void
     * @throws CannotPromoteAChildToHeadException
     * @throws CitizenIsNotPartOfTheFamilyException
     * @throws FamilyHasMoreThanSixMembersException
     * @throws MaximumFamiliesHeadException
     */
    public function join(Family $family, FamilyRoles|string $role, bool $isHead = false) : void {
        if (!$this->families()->where('id', $family->id)->exists())
            $family->citizens()->attach($this->id, ['role' => is_string($role) ? $role : $role->value, 'is_head' => false]);

        if ($isHead)
            $family->promoteAsHead($this->id);
    }

    /**
     * Leave a family
     *
     * @param Family $family
     * @return void
     * @throws CannotLeaveAFamilyAsHead
     * @throws CannotLeaveFamily
     */
    public function leave(Family $family, bool $is_moving = false): void {
        // if citizen is not part of the family, do nothing
        if (!($family = $this->families()->where('id', $family->id)->first()))
            return;

        // if citizen is head, throw exception
        if ($family->head_citizen()->id === $this->id)
            throw new CannotLeaveAFamilyAsHead();

        // if citizen is a child and is the only member of the family and has no other families, throw exception
        if (($family->pivot->role === FamilyRoles::CHILD->value)
            && $family->citizens()->count() === 1
            && $this->families()->count() === 1
            && !$is_moving
        )
            throw new CannotLeaveFamily();

        $family->citizens()->detach($this->id);
    }
}
