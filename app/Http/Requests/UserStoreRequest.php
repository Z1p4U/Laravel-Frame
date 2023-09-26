<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|min:3|max:20",
            "email" => "email|required|unique:users",
            "jd" => "required",
            "role" => "required",
            "phone" => "required|unique:users,phone",
            "agree" => "required|boolean",
            "position" => "required",
            "password" => "required|confirmed|min:6",
            "annual_leave" => "required|min:0",
            "casual_leave" => "required|min:0",
            "probation_leave" => "required|min:0",
            "unpaid_leave" => "required|min:0",
        ];
    }
}
