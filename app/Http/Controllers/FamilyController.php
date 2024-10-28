<?php

namespace App\Http\Controllers;

use App\Enumerations\FamilyRoles;
use App\Http\Requests\PromoteCitizenAsHeadRequest;
use App\Http\Resources\FamilyResource;
use App\Models\Family;

class FamilyController extends Controller
{
    public function promote_head(Family $family, PromoteCitizenAsHeadRequest $request) {
        $validated = $request->validated();

        if ($family->head_citizen()->id === $validated['citizen_id'])
            return new FamilyResource($family); // No need to promote - handling idempotence :)

        $citizen = $family->citizens()->where('id', $validated['citizen_id'])->first();

        if (!$citizen)
            return response()->json(['message' => 'Citizen is not part of the family'], 404);

        if ($citizen->pivot->role === FamilyRoles::CHILD->value)
            return response()->json(['message' => 'Cannot promote a child to head'], 400);

        if ($citizen->pivot->role === FamilyRoles::PARENT->value){
            if ($family->citizens()->count() > 6)
                return response()->json(['message' => 'Cannot promote a parent to head in a family with more than 6 citizens'], 400);

            if ($citizen->countHeadAndParentFamilies() === 3)
                return response()->json(['message' => 'Cannot promote this parent to head: is already parent and head of 3 families'], 400);
        }

        $family->promoteAsHead($citizen);
        return new FamilyResource($family);
    }
}
