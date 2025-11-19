<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\TreatmentSessionStoreRequest;
use App\Http\Resources\V1\TreatmentSessionMiniResource;
use App\Http\Resources\V1\TreatmentSessionResource;
use App\Models\V1\TreatmentSession;
use Illuminate\Http\Request;

class TreatmentSessionController extends Controller
{
    function index(Request $request)  {
        $sessions =  TreatmentSession::paginate(8);
        // TreatmentSessionRecourse
        return TreatmentSessionMiniResource::collection($sessions);
    }

    function show(Request $request, TreatmentSession $session) {


        $allowedIncludes = ['treatmentCourse', 'procedures', 'dentist'];


        $requestedIncludes = [];

        if ($request->has('include')) {
            $relationsFromRequest = explode(',', $request->input('include'));

            $trimmedRelations = array_map('trim', $relationsFromRequest);

            $requestedIncludes = array_filter($trimmedRelations, function ($relation) use ($allowedIncludes) {
                return in_array($relation, $allowedIncludes);
            });
        }

        if (!empty($requestedIncludes)) {
            $session->load($requestedIncludes);
        }

        return apiResponse(new TreatmentSessionResource($session));
    }

    public function store(TreatmentSessionStoreRequest $request) {
        $validated = $request->validated();
        $session = TreatmentSession::create($validated);
        return apiResponse(new TreatmentSessionResource($session), status: 201);
    }
}
