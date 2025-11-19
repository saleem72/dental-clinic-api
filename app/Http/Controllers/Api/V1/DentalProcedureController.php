<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\DentalProcedureStoreRequest;
use App\Http\Requests\V1\DentalProcedureUpdateRequest;
use App\Http\Resources\V1\DentalProcedureResource;
use App\Models\V1\DentalProcedure;
use Illuminate\Http\Request;

class DentalProcedureController extends Controller
{

    public function index(Request $request)
    {
        $procedures =  DentalProcedure::paginate(8);
        return DentalProcedureResource::collection($procedures);
    }

    public function show(Request $request, DentalProcedure $procedure) {
        return apiResponse(new DentalProcedureResource($procedure));
    }

    public function store(DentalProcedureStoreRequest $request) {
        $validated = $request->validated();
        $validated['is_active'] = true;
        $procedure = DentalProcedure::create($validated);
        return apiResponse(
            data:  new DentalProcedureResource($procedure),
            status: 201
        );
    }

    public function update(DentalProcedureUpdateRequest $request, DentalProcedure $procedure) {
        $validated = $request->validated();
        $procedure->update($validated);
        return apiResponse(
            data:  new DentalProcedureResource($procedure),
            status: 201
        );

    }
}
