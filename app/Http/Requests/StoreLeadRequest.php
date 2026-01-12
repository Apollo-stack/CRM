<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'client_id' => [
                'required',
                Rule::exists('clients', 'id')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                }),
            ],
            'value' => 'nullable|numeric|min:0',
            // Address fields
            'cep' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string|max:2',
        ];
    }
}
