<?php

namespace Database\Factories;

use App\Models\Domain;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Domain>
 */
class DomainFactory extends Factory
{
    protected $model = Domain::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'parent_id' => null,
            'slug' => Str::slug($name).'-'.fake()->unique()->numerify('###'),
            'icon' => null,
            'sort_order' => 0,
            'is_active' => true,
        ];
    }
}
