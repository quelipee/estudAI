<?php

namespace Database\Factories;

use App\Domains\CourseDomain\Enums\Status;
use App\Models\Course;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->text(10),
            'description' => fake()->text(20),
            'category' => fake()->text(10),
            'status' => Status::Pending,
        ];
    }
}
