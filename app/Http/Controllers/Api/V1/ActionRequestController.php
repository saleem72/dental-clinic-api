<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ActionRequestStatus;
use App\Enums\ActionRequestType;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\HandleActionRequest;
use App\Http\Requests\V1\PotentialPatientRequest;
use App\Http\Requests\V1\StoreActionRequest;
use App\Http\Resources\V1\ActionRequestResource;
use App\Models\V1\ActionRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ActionRequestController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request) {
        $this->authorize('viewAny', ActionRequest::class);
        $actions = ActionRequest::all();
        return apiResponse(ActionRequestResource::collection($actions));
    }

    public function store(StoreActionRequest $request) {
        $this->authorize('create', ActionRequest::class);
        $validated = $request->validated();
        $user = $request->user();
        $types = array_column(ActionRequestType::cases(), 'value');
        $status = array_column(ActionRequestStatus::cases(), 'value');
        // $types = ['hello'];
        // $status = ['world'];



        return apiResponse(
            status: 200,
            data: [
                'types' => $types,
                'status' => $status,
            ],
            success: true,
            message: 'Passed policy test',
        );

    }

    public function potentialPatient(PotentialPatientRequest $request)  {
        $this->authorize('create', ActionRequest::class);
        $user = $request->user();
        $validated = $request->validated();


        $actionRequest = ActionRequest::create([
            'created_by_id' => $user->id,
            'assigned_to_id' => $validated['assigned_to_id'],
            'type' => ActionRequestType::POTENTIAL_PATIENT,
            'status' => ActionRequestStatus::PENDING,
            'payload' => [
                'name'            => $validated['name'],
                'email'           => $validated['email'] ?? null,
                'phone'           => $validated['phone'],
                'gender'          => $validated['gender'],
                'date_of_birth'   => $validated['date_of_birth'] ?? null,
                'medical_notes'   => $validated['medical_notes'] ?? null,
                'medical_history' => $validated['medical_history'] ?? null,
            ],
        ]);

        return apiResponse(new ActionRequestResource($actionRequest));

    }

    public function handle(HandleActionRequest $request, ActionRequest $actionRequest) {
        $this->authorize('handle', $actionRequest);


        $user = $request->user();
        $user_id = $user->id;
        $roles = $user->roles->pluck('name');
        $assigned_to_id = $actionRequest->assigned_to_id;

        return apiResponse(
            status: 200,
            data: [
                'roles' => $roles,
                'user_id' => $user_id,
                'assigned_to_id' => $assigned_to_id,
            ],
            success: true,
            message: 'Passed policy test',
        );
    }
}


