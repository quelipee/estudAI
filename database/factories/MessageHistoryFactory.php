<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\MessageHistory;
use App\Models\Topic;
use App\Models\User;
use GeminiAPI\Enums\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MessageHistory>
 */
class MessageHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'message' => $this->faker->realText(),
            'role' => Role::User->name,
            'user_id' => User::first(),
            'course_id' => Course::first(),
            'topic_id' => Topic::first(),
        ];
    }
}
