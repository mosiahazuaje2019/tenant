<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:clients,email', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'The client name is required.',
            'email.required'    => 'The client email is required.',
            'email.unique'      => 'The email is already taken by another client or user.',
            'password.required' => 'The password field is required.',
            'password.min'      => 'The password must be at least 6 characters.',
        ];
    }
}
