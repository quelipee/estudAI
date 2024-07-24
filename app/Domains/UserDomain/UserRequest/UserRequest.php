<?php

namespace App\Domains\UserDomain\UserRequest;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'id' => ['required', 'integer', 'exists:users,id'],
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'email_verified_at' => ['nullable', 'date'],
        ];
    }
}
