<?php

namespace App\Http\Requests;

class StoreUserRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
            'national_id' => 'nullable|string|max:20|unique:users',
            'age' => 'nullable|integer|min:18',
            'code' => 'nullable|string|max:50',
        ];
    }
}
