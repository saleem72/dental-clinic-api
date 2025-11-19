<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ActionRequestActions;
use App\Enums\ActionRequestStatus;
use App\Enums\ActionRequestType;
use App\Enums\RolesStrings;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\HandleActionRequest;
use App\Http\Requests\V1\PotentialPatientRequest;
use App\Http\Requests\V1\StoreActionRequest;
use App\Http\Resources\V1\ActionRequestResource;
use App\Http\Resources\V1\PatientResource;
use App\Models\V1\ActionRequest;
use App\Services\ActionRequestService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ActionRequestController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', ActionRequest::class);

        $user = $request->user();
        $roles = $user->roles->pluck('name');

        // Always start with a fresh query
        $query = ActionRequest::query()
            ->where('status', ActionRequestStatus::PENDING->value)
            ->with(['assignee', 'creator']);

        if ($roles->contains(RolesStrings::MANAGER->value)) {
            // Managers see all pending requests
            $actions = $query->get();
        }
        elseif ($roles->contains(RolesStrings::DENTIST->value)) {
            // Dentists only see requests assigned to them
            $actions = $query
                ->where('assigned_to_id', $user->id)
                ->get();
        }
        elseif ($roles->contains(RolesStrings::RECEPTIONIST->value)) {
            // Receptionists only see requests they created
            $actions = $query
                ->where('created_by_id', $user->id)
                ->get();
        }
        else {
            // Zero access for unknown roles
            $actions = collect();
        }

        return apiResponse(ActionRequestResource::collection($actions));
    }

    public function store(StoreActionRequest $request)
    {
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

    public function show(ActionRequest $actionRequest) {
        $this->authorize('view', $actionRequest);
        return apiResponse(new ActionRequestResource($actionRequest));
    }

    public function potentialPatient(PotentialPatientRequest $request)
    {
        $this->authorize('create', ActionRequest::class);
        $user = $request->user();
        $validated = $request->validated();


        $actionRequest = ActionRequest::create([
            'created_by_id' => $user->id,
            'assigned_to_id' => $validated['assigned_to_id'],
            'handled_by_id' => $validated['assigned_to_id'],
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
                'assigned_to_id'  => $validated['assigned_to_id'],
            ],
        ]);

        return apiResponse(new ActionRequestResource($actionRequest));
    }

    public function handle(HandleActionRequest $request, ActionRequest $actionRequest, ActionRequestService $service)
    {
        $this->authorize('handle', $actionRequest);
        $user = $request->user();
        $data = $request->validated();
        $action = ActionRequestActions::from($data['action']);
        try {
            $patient = $service->handle(
                $user->id,
                $actionRequest,
                $action,
                $data->notes ?? null
            );
        } catch (\Throwable $th) {
            return apiResponse(
                status: 400,
                success: false,
                message: 'some error happen:' . $th->getMessage(),
            );
        }

        if ($data['action'] === ActionRequestActions::APPROVE && $patient) {
            return apiResponse(
                status: 201,
                success: true,
                message: 'ActionRequest handled successfully',
                data: [
                    'action_request' => $actionRequest,
                    'patient' => $patient ? new PatientResource($patient) : null,
                ]
            );
        }

        return apiResponse(
            status: 200,
            success: true,
            message: 'ActionRequest handled successfully',
            data: $actionRequest
        );
    }
}
