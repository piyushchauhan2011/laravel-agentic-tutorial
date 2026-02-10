<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePositionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $position = $this->route('position');

        return $this->user()?->can('update', $position) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'department' => ['sometimes', 'nullable', 'string', 'max:255'],
            'employment_type' => ['sometimes', 'in:full_time,part_time,contract,internship'],
            'location' => ['sometimes', 'nullable', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'in:draft,published,closed'],
            'published_at' => ['sometimes', 'nullable', 'date'],
            'closing_at' => ['sometimes', 'nullable', 'date', 'after:published_at'],
        ];
    }
}
