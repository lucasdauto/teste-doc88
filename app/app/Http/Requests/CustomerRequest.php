<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:customers',
            'phone' => 'required|string|max:19',
            'birthdate' => 'required|date_format:Y-m-d',
            'address' => 'required|string|max:100',
            'neighborhood' => 'required|string|max:100',
            'city' => 'required|string|max:45',
            'zip_code' => 'required|string|max:10',
            'complement' => 'nullable|string|max:25',
        ];
    }
}
