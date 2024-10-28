<?php

namespace App\Models;

use App\Enumerations\FamilyRoles;
use App\Exceptions\CannotPromoteAChildToHeadException;
use App\Exceptions\CitizenIsNotPartOfTheFamilyException;
use App\Exceptions\FamilyHasMoreThanSixMembersException;
use App\Exceptions\MaximumFamiliesHeadException;
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
     * @throws MaximumFamiliesHeadException
     * @throws CitizenIsNotPartOfTheFamilyException
     * @throws CannotPromoteAChildToHeadException
     * @throws FamilyHasMoreThanSixMembersException
     */
    public function promoteAsHead(int $citizen_id) : bool {
        $citizen = $this->citizens()->where('id', $citizen_id)->first();

        if (!$citizen)
            throw new CitizenIsNotPartOfTheFamilyException();

        if ($citizen->pivot->role === FamilyRoles::CHILD->value)
            throw new CannotPromoteAChildToHeadException();

        if ($citizen->pivot->role === FamilyRoles::PARENT->value){
            if ($this->citizens()->count() > 6)
                throw new FamilyHasMoreThanSixMembersException();

            if ($citizen->countHeadAndParentFamilies() === 3)
                throw new MaximumFamiliesHeadException();
        }

        $this->head_citizen()->pivot->update(['is_head' => false]);
        return $citizen->pivot->update(['is_head' => true]);
    }
}
