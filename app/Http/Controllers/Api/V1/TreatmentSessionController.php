<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\V1\TreatmentCourse;
use App\Models\V1\TreatmentSession;
use Illuminate\Http\Request;

class TreatmentSessionController extends Controller
{
    function index(Request $request)  {
        return TreatmentSession::paginate(15);

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

        return apiResponse($session);
    }
}
