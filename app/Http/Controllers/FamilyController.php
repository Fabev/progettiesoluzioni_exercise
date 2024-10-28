<?php

namespace App\Http\Controllers;

use App\Http\Requests\JoinFamilyRequest;
use App\Http\Requests\PromoteCitizenAsHeadRequest;
use App\Http\Resources\FamilyResource;
use App\Models\Citizen;
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
    public function promote_head(Family $family, Citizen $citizen): \Illuminate\Http\JsonResponse|FamilyResource {
        if ($family->head_citizen()->id === $citizen->id)
            return new FamilyResource($family); // No need to promote - handling idempotence :)

        try {
            $family->promoteAsHead($citizen->id);
        } catch (\Throwable $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }
        return new FamilyResource($family);
    }

    /**
     * Remove a citizen from the family
     *
     * @param Family $family
     * @param Citizen $citizen
     * @return FamilyResource|\Illuminate\Http\JsonResponse
     */
    public function remove(Family $family, Citizen $citizen) {
        try {
            $citizen->leave($family);
        } catch (\Throwable $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        return new FamilyResource($family);
    }

    public function join(Family $family, JoinFamilyRequest $request) {
        $validated = $request->validated();
        $citizen = Citizen::find($validated['citizen_id']);
        try {
            $citizen->join($family, $validated['role']);
        } catch (\Throwable $exception) {
            return response()->json(['message' => $exception->getMessage()], 400);
        }

        return new FamilyResource($family);
    }
}
