<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\LeadStatus;

class UpdateLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // If it's a quick status update
        if ($this->has('status') && !$this->has('title')) {
            return [
                'status' => ['required', 'string'], // In a real app, validate against Enum
            ];
        }

        // Full update
        return [
            'title' => 'required|string|max:255',
            'client_id' => [
                'required',
                Rule::exists('clients', 'id')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                }),
            ],
            'value' => 'nullable|numeric|min:0',
            'status' => 'nullable|string',
             // Address fields
            'cep' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string|max:2',
        ];
    }
}
