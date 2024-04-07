<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreateReceptionistRequest extends FormRequest
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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'numero_ID' => 'string|unique:guests,numero_ID', // Assuming your table name is 'users'
            'gender' => 'required|in:male,female',
            'address' => 'required|string',
            'date_of_birth' => 'required|date',
            'phone_number' => 'required|string',
            'email' => 'required|email|unique:receptionists,email', // Assuming your table name is 'users'
            'password' => [
                'required',
                'string',
                'min:4',
                'confirmed',
            ],
            'image_profile'=> 'image|mimes:png,jpg,jpeg,svg|max:10240',
        ];
    }
}
