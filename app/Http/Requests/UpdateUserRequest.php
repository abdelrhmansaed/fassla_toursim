<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UpdateUserRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user)
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array',
            'national_id' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('users')->ignore($this->user)
            ],
            'age' => 'nullable|integer|min:18',
            'code' => 'nullable|string|max:50',
        ];
    }

}
