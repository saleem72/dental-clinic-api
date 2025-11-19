<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CreatePatientRequest;
use App\Http\Resources\V1\PatientResource;
use App\Models\V1\Patient;
use App\Models\V1\User;
use App\Services\UsersService;
use Illuminate\Http\Request;

class PatientsController extends Controller
{
    // index
    public function index(Request $request)
    {
        // Define a whitelist of relationships that are allowed to be included
        $allowedIncludes = [
            'user',
            'dentist'
        ];

        $query = Patient::query();
        $requestedIncludes = [];

        // 1. Check if the 'include' query parameter is present
        if ($request->has('include')) {
            // Get the relations as an array from the request
            $relationsFromRequest = explode(',', $request->input('include'));

            // 2. Filter the requested relations against the whitelist
            $requestedIncludes = array_filter($relationsFromRequest, function ($relation) use ($allowedIncludes) {
                return in_array($relation, $allowedIncludes);
            });
        }

        // 3. Eager load only the *whitelisted* and *requested* relations
        if (!empty($requestedIncludes)) {
            $query->with($requestedIncludes);
        }

        $users = $query->get();

        return apiResponse(PatientResource::collection($users));
    }

    public function createPatient(CreatePatientRequest $request, UsersService $service)
    {
        $currentUser = $request->user();
        $validated = $request->validated();

        $created = $service->createPatient($currentUser->id, $validated);

        if (!$created) {
            return apiResponse(
                message: 'Failed to create patient',
                status: 500
            );
        }

        $patient = $created->patient;

        return apiResponse(
            data: new PatientResource($patient),
            message: 'Patient created successfully',
            status: 201
        );
    }

    // public function searchPatients(Request $request) {
    public function searchPatients(Request $request)
    {
        $query = Patient::query()
            ->with('user') // eager load to avoid N+1
            ->when($request->filled('name'), function ($q) use ($request) {
                $q->whereHas(
                    'user',
                    fn($u) =>
                    $u->where('name', 'like', '%' . $request->name . '%')
                );
            })
            ->when($request->filled('email'), function ($q) use ($request) {
                $q->whereHas(
                    'user',
                    fn($u) =>
                    $u->where('email', 'like', '%' . $request->email . '%')
                );
            })
            ->when($request->filled('phone'), function ($q) use ($request) {
                $q->whereHas(
                    'user',
                    fn($u) =>
                    $u->where('phone', 'like', '%' . $request->phone . '%')
                );
            })
            ->when($request->filled('gender'), function ($q) use ($request) {
                $q->where('gender', $request->gender);
            })
            ->when($request->filled('dentist_id'), function ($q) use ($request) {
                $q->where('dentist_id', $request->dentist_id);
            })
            ->when($request->filled('is_active'), function ($q) use ($request) {
                $q->whereHas(
                    'user',
                    fn($u) =>
                    $u->where('is_active', $request->boolean('is_active'))
                );
            })
            ->when($request->filled('patient_code'), function ($q) use ($request) {
                $q->where('patient_code', 'like', '%' . $request->patient_code . '%');
            })
            ->when($request->filled('date_from'), function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->date_to);
            });

        // Optional sorting
        if ($request->filled('sort_by')) {
            $direction = $request->get('sort_dir', 'asc');
            if (in_array($request->sort_by, ['name', 'email', 'username', 'phone'])) {
                $query->orderBy(
                    User::select($request->sort_by)
                        ->whereColumn('users.id', 'patients.user_id'),
                    $direction
                );
            } else {
                $query->orderBy($request->sort_by, $direction);
            }
        }

        $patients = $query->paginate($request->get('per_page', 20));

        return apiResponse(
            data: PatientResource::collection($patients),
            success: true
        );
    }

    function show(Request $request, Patient $patient)
    {


        $includeDentist = $request->boolean('include_dentist');

        if ($includeDentist) {
            // Load the relationship or perform a specific action
            $patient->load('dentist');
        }

        return apiResponse(new PatientResource($patient));
    }
}
