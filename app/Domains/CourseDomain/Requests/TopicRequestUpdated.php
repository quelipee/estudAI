<?php

namespace App\Domains\CourseDomain\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TopicRequestUpdated extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'topic' => ['required', 'string'],
            'course_id' => ['required', 'exists:App\Models\Course,id'],
            'roleUserId' => ['required', 'exists:App\Models\MessageHistory,id'],
            'roleModelId' => ['required', 'exists:App\Models\MessageHistory,id'],
        ];
    }
}
