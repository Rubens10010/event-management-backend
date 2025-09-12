<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'organization_id' => 'required|exists:organizations,id',
            'capacity' => 'nullable|integer|min:1',
            'max_invitees' => 'nullable|integer|min:1',
            'starts_at' => 'required|date',
            'finishes_at' => 'required|date|after:starts_at',
            'banner' => 'nullable|image|max:2048',
        ];
    }
}
