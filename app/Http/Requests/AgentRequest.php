<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgentRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'age' => 'nullable|integer|min:18',
            'national_id' => 'required|numeric|unique:users,national_id',
            'code'=>'required|numeric'
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'الاسم مطلوب.',
            'name.string' => 'يجب أن يكون الاسم نصيًا.',
            'name.max' => 'يجب ألا يتجاوز الاسم 255 حرفًا.',

            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'يجب إدخال بريد إلكتروني صالح.',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل.',

            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min' => 'يجب أن تتكون كلمة المرور من 6 أحرف على الأقل.',

            'age.integer' => 'يجب أن يكون السن رقمًا صحيحًا.',
            'age.min' => 'يجب ألا يقل العمر عن 18 عامًا.',

            'national_id.required' => 'رقم الهوية مطلوب.',
            'national_id.numeric' => 'يجب أن يكون رقم الهوية رقمًا.',
            'national_id.digits' => 'يجب أن يكون رقم الهوية يجب ان يكون مكون من 14 رقمًا.',
            'national_id.unique' => 'رقم الهوية مستخدم بالفعل.',
        ];
    }
}
