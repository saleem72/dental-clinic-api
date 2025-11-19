<?php

namespace App\Helpers;

use App\Models\V1\TreatmentSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AppointmentGenerator
{
    /**
     * Pick next available slot for dentist
     */
    public static function suggestNextAvailableSlot(
        int $dentistId,
        Carbon $requestedStartAt,
        int $durationMinutes,
        ?int $ignoreSessionId = null
    ): Carbon {

        $start = $requestedStartAt->copy();
        $end   = $start->copy()->addMinutes($durationMinutes);

        while (self::isOverlapping($dentistId, $start, $end, $ignoreSessionId)) {
            $start->addMinutes(15);
            $end->addMinutes(15);
        }

        return $start;
    }

    protected static function isOverlapping(
        int $dentistId,
        Carbon $start,
        Carbon $end,
        ?int $ignoreSessionId = null
    ): bool {
        return TreatmentSession::where('dentist_id', $dentistId)
            ->when($ignoreSessionId, fn($q) => $q->where('id', '!=', $ignoreSessionId))
            ->whereNot('status', 'cancelled')
            ->where(function ($q) use ($start, $end) {

                // check if another session starts inside the requested window
                $q->whereBetween('start_at', [$start, $end])

                    // check if another session ends inside the requested window
                    ->orWhereBetween(
                        DB::raw("DATE_ADD(start_at, INTERVAL estimated_time MINUTE)"),
                        [$start, $end]
                    )

                    // check if another session completely covers the requested window
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('start_at', '<=', $start)
                           ->where(
                               DB::raw("DATE_ADD(start_at, INTERVAL estimated_time MINUTE)"),
                               '>=',
                               $end
                           );
                    });
            })
            ->exists();
    }

    /**
     * Return requested time if free, otherwise next free slot
     */
    public static function resolveAvailableSlot(
        int $dentistId,
        Carbon $requestedStartAt,
        int $durationMinutes,
        ?int $ignoreSessionId = null
    ): Carbon {

        if (! static::isOverlapping($dentistId, $requestedStartAt, $requestedStartAt->copy()->addMinutes($durationMinutes), $ignoreSessionId)) {
            return $requestedStartAt;
        }

        return static::suggestNextAvailableSlot(
            dentistId: $dentistId,
            requestedStartAt: $requestedStartAt,
            durationMinutes: $durationMinutes,
            ignoreSessionId: $ignoreSessionId
        );
    }
}
