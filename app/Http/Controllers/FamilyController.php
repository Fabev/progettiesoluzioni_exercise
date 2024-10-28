<?php

namespace App\Http\Controllers;

use App\Http\Requests\PromoteCitizenAsHeadRequest;
use App\Http\Resources\FamilyResource;
use App\Models\Family;

class FamilyController extends Controller
{
    /**
     * Promote a citizen as head of the family
     *
     * @param Family $family
     * @param PromoteCitizenAsHeadRequest $request
     * @return FamilyResource|\Illuminate\Http\JsonResponse
     */
    public function promote_head(Family $family, PromoteCitizenAsHeadRequest $request): \Illuminate\Http\JsonResponse|FamilyResource {
        $validated = $request->validated();

        if ($family->head_citizen()->id === $validated['citizen_id'])
            return new FamilyResource($family); // No need to promote - handling idempotence :)

        try {
            $family->promoteAsHead($validated['citizen_id']);
        } catch (\Throwable $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }
        return new FamilyResource($family);
    }
}
