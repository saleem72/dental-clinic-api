<?php

namespace Database\Factories\V1;

use App\Models\V1\Patient;
use App\Models\V1\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */

class PatientFactory extends Factory
{
    /**
     * The model that this factory is for.
     */
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_code'    => 'P' . $this->faker->unique()->numberBetween(1000, 9999),
            'gender'          => $this->faker->randomElement(['male', 'female']),
            'date_of_birth'   => $this->faker->date('Y-m-d', '2000-01-01'),
            'medical_notes'   => $this->faker->sentence,
            'medical_history' => $this->faker->sentence,
            'dentist_id'      => null, // can be set dynamically
        ];
    }

    /**
     * Optional: allow passing a dentist_id dynamically.
     *
     * @param int $dentistId
     * @return static
     */
    public function withDentist(int $dentistId)
    {
        return $this->state(fn(array $attributes) => [
            'dentist_id' => $dentistId,
        ]);
    }
}
