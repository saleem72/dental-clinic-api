<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\TreatmentCourseStoreRequest;
use App\Http\Resources\V1\TreatmentResource;
use App\Models\V1\DentalProcedure;
use App\Models\V1\TreatmentCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TreatmentsController extends Controller
{
    function index(Request $request)
    {
        $allowedIncludes = ['patient', 'dentist'];


        $requestedIncludes = [];

        if ($request->has('include')) {
            $relationsFromRequest = explode(',', $request->input('include'));

            $trimmedRelations = array_map('trim', $relationsFromRequest);

            $requestedIncludes = array_filter($trimmedRelations, function ($relation) use ($allowedIncludes) {
                return in_array($relation, $allowedIncludes);
            });
        }

        $query = TreatmentCourse::query();

        if (!empty($requestedIncludes)) {
            $query->with($requestedIncludes);
        }

        $treatments = $query->paginate(8);

        return  TreatmentResource::collection($treatments);
    }

    function show(Request $request, TreatmentCourse $treatment)
    {
        $allowedIncludes = ['patient', 'dentist', 'treatmentSessions'];


        $requestedIncludes = [];

        if ($request->has('include')) {
            $relationsFromRequest = explode(',', $request->input('include'));

            $trimmedRelations = array_map('trim', $relationsFromRequest);

            $requestedIncludes = array_filter($trimmedRelations, function ($relation) use ($allowedIncludes) {
                return in_array($relation, $allowedIncludes);
            });
        }

        if (!empty($requestedIncludes)) {
            $treatment->load($requestedIncludes);
        }


        return apiResponse(
            message: 'Treatment was retrieved successfully',
            data:  new TreatmentResource($treatment), // $requestedIncludes, //
        );
    }

    function store(TreatmentCourseStoreRequest $request)
    {
        $validated = $request->validated();

        $data = Arr::only(
            $validated,
            [
                'patient_id',
                'dentist_id',
                'started_at',
                'completed_at',
                'notes',
                'status',
            ],
        );

        $treatment =  TreatmentCourse::create($data);
        return apiResponse(
            data: new TreatmentResource($treatment),
            message: 'Treatment started successfully!',
            status: 201,
        );
    }
}
