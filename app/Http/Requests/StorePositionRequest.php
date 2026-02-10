<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePositionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\Position::class) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'title' => ['required', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'employment_type' => ['required', 'in:full_time,part_time,contract,internship'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published,closed'],
            'published_at' => ['nullable', 'date'],
            'closing_at' => ['nullable', 'date', 'after:published_at'],
        ];
    }
}
