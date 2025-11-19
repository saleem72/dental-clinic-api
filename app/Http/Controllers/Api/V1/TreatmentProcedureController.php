<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\IncludesExtractor;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\TreatmentProcedureStoreRequest;
use App\Http\Resources\V1\TreatmentProcedureMiniResource;
use App\Http\Resources\V1\TreatmentProcedureResource;
use App\Models\V1\TreatmentProcedure;
use Illuminate\Http\Request;

class TreatmentProcedureController extends Controller
{
    public function index(Request $request) {
        $procedures = TreatmentProcedure::paginate(8);
        return TreatmentProcedureMiniResource::collection($procedures);
    }

    public function show(Request $request, TreatmentProcedure $procedure) {
        $allowedIncludes =  ['treatment', 'session', 'dentist', 'procedure'];
        $replacements = collect([
            'treatment' => 'treatmentCourse',
            'procedure' =>  'dentalProcedure',
        ]);

        $requestedIncludes = [];
        if ($request->has('include')) {
            $requestedIncludes = IncludesExtractor::extract(
                $allowedIncludes,
                explode(',', $request->input('include')),
                $replacements
            );
        }

        $procedure->load($requestedIncludes);

        return apiResponse(new TreatmentProcedureResource($procedure));
    }

    public function store(TreatmentProcedureStoreRequest $request) {
        $validated = $request->validated();

        $procedure = TreatmentProcedure::create($validated);

        $procedure->load(['dentist', 'session', 'treatmentCourse', 'dentalProcedure']);

        return apiResponse(data: new TreatmentProcedureResource($procedure), status: 201);
    }
}


