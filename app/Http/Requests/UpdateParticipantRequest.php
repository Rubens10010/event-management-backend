<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateParticipantRequest extends FormRequest
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
            'team_id' => 'nullable|exists:teams,id',
            'ndoc' => 'required|digits:8|unique:participants,ndoc,' . $this->participant->id,
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:participants,email,' . $this->participant->id,
            'phone' => 'nullable|digits:9',
        ];
    }
}
