<?php

namespace App\Services;

use App\Models\V1\ActionRequest;
use App\Enums\ActionRequestActions;
use App\Enums\ActionRequestType;
use App\Enums\ActionRequestStatus;
use App\Helpers\AppointmentGenerator;
use App\Models\V1\TreatmentSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ActionRequestService
{
    public function __construct(
        private readonly UsersService $usersService
    ) {}

    public function handle(
        int $currentUserId,
        ActionRequest $actionRequest,
        ActionRequestActions $action,
        ?string $notes = null,
    ) {
        if ($actionRequest->status !== ActionRequestStatus::PENDING) {
            throw new \Exception('This request has already been handled.');
        }

        $patient = null;

        DB::transaction(function () use (
            $currentUserId,
            $actionRequest,
            $action,
            $notes,
            &$patient
        ) {
            switch ($action) {

                case ActionRequestActions::APPROVE:
                    if ($actionRequest->type === ActionRequestType::POTENTIAL_PATIENT) {
                        $patient = $this->usersService
                            ->createPatientFromActionRequest($currentUserId, $actionRequest);
                    }

                    if ($actionRequest->type === ActionRequestType::RESCHEDULE_SESSION) {

                        $session = TreatmentSession::findOrFail($actionRequest->payload['session_id']);

                        $requested = Carbon::parse($actionRequest->payload['suggested_start_at']);
                        $duration  = $session->estimated_time;

                        $start = AppointmentGenerator::resolveAvailableSlot(
                            dentistId: $session->dentist_id,
                            requestedStartAt: $requested,
                            durationMinutes: $duration,
                            ignoreSessionId: $session->id
                        );

                        $session->update(['start_at' => $start]);
                    }
                    $actionRequest->status = ActionRequestStatus::APPROVED->value;
                    break;

                case ActionRequestActions::REJECT:
                    $actionRequest->status = ActionRequestStatus::REJECTED->value;
                    break;

                case ActionRequestActions::COMPLETE:
                    $actionRequest->status = ActionRequestStatus::COMPLETED->value;
                    break;
            }

            $actionRequest->handled_by_id = $currentUserId;
            $actionRequest->doctor_note   = $notes;
            $actionRequest->resolved_at   = now();

            $actionRequest->save();
        });

        return $patient;
    }
}
