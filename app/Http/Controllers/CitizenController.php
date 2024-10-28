<?php

namespace App\Http\Controllers;

use App\Http\Requests\MoveCitizenToFamilyRequest;
use App\Http\Resources\CitizenResource;
use App\Models\Citizen;
use App\Models\Family;
use Illuminate\Http\JsonResponse;

class CitizenController extends Controller
{
    /**
     * Move a citizen from a family to another
     *
     * @param Citizen $citizen
     * @param MoveCitizenToFamilyRequest $request
     * @return CitizenResource|JsonResponse
     */
    public function move(Citizen $citizen, MoveCitizenToFamilyRequest $request) : JsonResponse|CitizenResource {
        $validated = $request->validated();
        $fromFamily = $citizen->families()->where('id', $validated['family_from_id'])->first();
        $toFamily = Family::find($validated['family_to_id']);

        if (!$fromFamily)
            return response()->json(['message' => 'Citizen is not part of the specified family'], 400);

        try {
            $citizen->leave($fromFamily, true);
            $citizen->join($toFamily, $fromFamily->pivot->role);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

        return new CitizenResource($citizen);
    }
}
