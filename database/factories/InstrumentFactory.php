<?php

namespace Database\Factories;

use App\Models\Instrument;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstrumentFactory extends Factory
{
	protected $model = Instrument::class;

	public function definition(): array
	{
		return [
            'name' => $this->faker->word,
            'hourly_rate' => $this->faker->randomFloat(2, 0, 100),
		];
	}
}
