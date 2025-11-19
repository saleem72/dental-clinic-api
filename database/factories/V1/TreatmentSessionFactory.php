<?php

namespace Database\Factories\V1;

use App\Models\V1\TreatmentCourse;
use App\Models\V1\TreatmentProcedure;
use App\Models\V1\TreatmentSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\V1\TreatmentSession>
 */
class TreatmentSessionFactory extends Factory
{
    protected $model = TreatmentSession::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Default values, will likely be overridden by `forTreatment()`
            'treatment_course_id'   => null,
            'dentist_id'     => null,
            'start_at'       => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'estimated_time' => $this->faker->numberBetween(30, 90),
            'notes'          => $this->faker->sentence,
            'status'         => $this->faker->randomElement(['scheduled', 'in_progress', 'completed', 'cancelled']),
        ];
    }

    /**
     * Assign this session to a specific treatment.
     */
    public function forTreatment(int $treatmentId)
    {
        return $this->state(function (array $attributes) use ($treatmentId) {
            $treatment = TreatmentCourse::findOrFail($treatmentId ?? 1);

            return [
                'treatment_course_id'   => $treatment->id,
                'dentist_id'     => $treatment->dentist_id,
                'start_at'       => $this->faker->dateTimeBetween('-1 month', '+1 month'),
                'estimated_time' => $this->faker->numberBetween(30, 90),
                'notes'          => $this->faker->sentence,
                'status'         => $this->faker->randomElement(['scheduled', 'in_progress', 'completed', 'cancelled']),
            ];
        });
    }

    /**
     * After creating a session, generate 2–3 procedures automatically.
     */
    public function configure()
    {
        return $this->afterCreating(function (TreatmentSession $session) {
            // Generate random 2–3 procedures for this session
            TreatmentProcedure::factory()
                ->count(rand(2, 3))
                ->forTreatment($session->treatment_course_id)
                ->forSession($session->id)
                ->create();
        });
    }
}
