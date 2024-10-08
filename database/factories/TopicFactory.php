<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Topic>
 */
class TopicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->text(10),
            'topic' => $this->faker->text(),
            'course_id' => Course::first(),
        ];
    }
}
