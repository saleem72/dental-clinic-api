<?php

namespace Database\Factories\V1;

use App\Enums\TreatmentStatus;
use App\Models\V1\Patient;
use App\Models\V1\TreatmentCourse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\V1\TreatmentCourse>
 */
class TreatmentCourseFactory extends Factory
{
    protected $model = TreatmentCourse::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'patient_id'   => null, // must be set via forPatient()
            'dentist_id'   => null, // automatically derived in forPatient()
            'started_at'   => $this->faker->dateTimeBetween('-1 year', 'now'),
            'completed_at' => null,
            'notes'        => $this->faker->sentence,
            'status'       => $this->faker->randomElement(['active', 'completed', 'cancelled']),
        ];
    }

    /**
     * Attach a specific patient to the treatment course.
     * Automatically sets the dentist_id from the patient if not provided.
     */
    public function forPatient(int $patientId, ?int $dentistId = null)
    {
        return $this->state(function (array $attributes) use ($patientId, $dentistId) {
            $dentistId ??= Patient::findOrFail($patientId)->dentist_id;

            return [
                'patient_id'   => $patientId,
                'dentist_id'   => $dentistId,
                'started_at'   => $this->faker->dateTimeBetween('-1 year', 'now'),
                'completed_at' => null,
                'notes'        => $this->faker->sentence,
                'status'       => $this->faker->randomElement(['active', 'completed', 'cancelled']),
            ];
        });
    }

    /**
     * Mark course as completed and set completed_at date.
     */
    public function completed()
    {
        return $this->state(function (array $attributes) {
            $start = Carbon::parse($attributes['started_at'] ?? now());
            return [
                'status'       => 'completed',
                'completed_at' => $start->copy()->addDays(rand(1, 30)),
            ];
        });
    }

    /**
     * Mark course as active.
     */
    public function active()
    {
        return $this->state([
            'status'       => 'active',
            'completed_at' => null,
        ]);
    }

    /**
     * Mark course as cancelled.
     */
    public function cancelled()
    {
        return $this->state([
            'status'       => 'cancelled',
            'completed_at' => null,
        ]);
    }
}
