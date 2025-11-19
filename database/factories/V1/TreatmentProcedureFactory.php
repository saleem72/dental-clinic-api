<?php

namespace Database\Factories\V1;

use App\Enums\Tooth;
use App\Models\V1\DentalProcedure;
use App\Models\V1\TreatmentCourse;
use App\Models\V1\TreatmentProcedure;
use App\Models\V1\TreatmentSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\V1\TreatmentProcedure>
 */
class TreatmentProcedureFactory extends Factory
{
    protected $model = TreatmentProcedure::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'treatment_course_id'        => null, // to be set by forTreatment()
            'treatment_session_id'          => null, // to be set by forSession()
            'dentist_id'          => null, // optionally from treatment or session
            'dental_procedure_id' => DentalProcedure::inRandomOrder()->first()?->id ?? 1,
            'tooth_code'          => $this->faker->optional()->randomElement(array_column(Tooth::cases(), 'value')),
            'cost'                => $this->faker->randomFloat(2, 50, 500),
            'notes'               => $this->faker->sentence,
            'performed_at'        => $this->faker->optional()->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * Assign this procedure to a specific treatment.
     */
    public function forTreatment(int $treatmentId)
    {
        return $this->state(function (array $attributes) use ($treatmentId) {
            $treatment = TreatmentCourse::findOrFail($treatmentId);
            return [
                'treatment_course_id' => $treatment->id,
                'dentist_id'   => $treatment->dentist_id,
            ];
        });
    }

    /**
     * Assign this procedure to a specific session.
     */
    public function forSession(int $sessionId)
    {
        return $this->state(function (array $attributes) use ($sessionId) {
            $session = TreatmentSession::findOrFail($sessionId);
            return [
                'treatment_session_id'   => $session->id,
                'dentist_id'   => $session->dentist_id,
            ];
        });
    }

    /**
     * Optionally assign a specific dentist.
     */
    public function withDentist(int $dentistId)
    {
        return $this->state(fn(array $attributes) => [
            'dentist_id' => $dentistId,
        ]);
    }
}
