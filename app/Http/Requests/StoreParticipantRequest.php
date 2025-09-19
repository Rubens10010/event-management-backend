<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreParticipantRequest extends FormRequest
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
            'event_id' => 'required|exists:events,id',
            'team_id' => 'nullable|exists:teams,id',
            'ndoc' => 'required|digits:8|unique:participants,ndoc',
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:participants,email',
            'phone' => 'nullable|digits:9',
        ];
    }
}
