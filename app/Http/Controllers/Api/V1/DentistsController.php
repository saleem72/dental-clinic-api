<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CreateDentistRequest;
use App\Http\Resources\V1\DentistResource;
use App\Models\V1\Dentist;
use App\Models\V1\User;
use App\Services\UsersService;
use Illuminate\Http\Request;

class DentistsController extends Controller
{

    public function index(Request $request)  {
        $allowedIncludes = [
            'user',
            'patients'
        ];

        $query = Dentist::query();
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

        return apiResponse(DentistResource::collection($users));
    }

    public function createDentist(CreateDentistRequest $request, UsersService $service)  {
        $currentUser = $request->user();
        $validated = $request->validated();

        $created = $service->createDentist($currentUser->id, $validated);

        if (!$created) {
            return apiResponse(
                message: 'Failed to create patient',
                status: 500
            );
        }

        $dentist = $created->dentist;

        return apiResponse(
            data: new DentistResource($dentist),
            message: 'Dentist created successfully',
            status: 201
        );
    }


    public function searchDentists(Request $request)
    {
        $query = Dentist::query()
            ->with('user') // eager load for performance
            ->when($request->filled('name'), function ($q) use ($request) {
                $q->whereHas('user', fn($u) =>
                    $u->where('name', 'like', '%' . $request->name . '%')
                );
            })
            ->when($request->filled('email'), function ($q) use ($request) {
                $q->whereHas('user', fn($u) =>
                    $u->where('email', 'like', '%' . $request->email . '%')
                );
            })
            ->when($request->filled('phone'), function ($q) use ($request) {
                $q->whereHas('user', fn($u) =>
                    $u->where('phone', 'like', '%' . $request->phone . '%')
                );
            })
            ->when($request->filled('username'), function ($q) use ($request) {
                $q->whereHas('user', fn($u) =>
                    $u->where('username', 'like', '%' . $request->username . '%')
                );
            })
            ->when($request->filled('license_number'), function ($q) use ($request) {
                $q->where('license_number', 'like', '%' . $request->license_number . '%');
            })
            ->when($request->filled('specialization'), function ($q) use ($request) {
                $q->where('specialization', 'like', '%' . $request->specialization . '%');
            })
            ->when($request->filled('is_available'), function ($q) use ($request) {
                $q->where('is_available', $request->boolean('is_available'));
            })
            ->when($request->filled('is_active'), function ($q) use ($request) {
                $q->whereHas('user', fn($u) =>
                    $u->where('is_active', $request->boolean('is_active'))
                );
            })
            ->when($request->filled('date_from'), function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->date_to);
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = $request->search;
                $q->where(function ($sub) use ($term) {
                    $sub->where('license_number', 'like', "%$term%")
                        ->orWhere('specialization', 'like', "%$term%")
                        ->orWhereHas('user', fn($u) =>
                            $u->where('name', 'like', "%$term%")
                            ->orWhere('email', 'like', "%$term%")
                            ->orWhere('phone', 'like', "%$term%")
                            ->orWhere('username', 'like', "%$term%")
                        );
                });
            });

        // Optional sorting
        if ($request->filled('sort_by')) {
            $direction = $request->get('sort_dir', 'asc');
            if (in_array($request->sort_by, ['name', 'email', 'username', 'phone'])) {
                $query->orderBy(User::select($request->sort_by)
                    ->whereColumn('users.id', 'dentists.user_id'),
                    $direction
                );
            } else {
                $query->orderBy($request->sort_by, $direction);
            }
        }

        $dentists = $query->paginate($request->get('per_page', 20));

        return apiResponse(
            data: DentistResource::collection($dentists),
            success: true
        );
    }


}
