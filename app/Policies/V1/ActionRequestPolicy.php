<?php

namespace App\Policies\V1;

use App\Models\V1\ActionRequest;
use App\Models\V1\User;
use Illuminate\Http\Request;

class ActionRequestPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['manager', 'receptionist', 'dentist']);
    }

    public function viewAny(User $user): bool
    {
        // return $user->hasRole(['manager']) || $request->created_by_id === $user->id
        //     || $request->assigned_to_id === $user->id;
        return $user->hasRole(['manager', 'receptionist', 'dentist']);
    }

    public function view(User $user, ActionRequest $request): bool
    {
        return $user->hasRole(['manager']) || $request->created_by_id === $user->id
            || $request->assigned_to_id === $user->id;
    }

    public function handle(User $user, ActionRequest $request): bool
    {
        return $user->hasRole('dentist')
            && $request->assigned_to_id === $user->dentist->id;
    }
}
