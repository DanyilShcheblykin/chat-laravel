<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function rules()
    {
        return [
            "email" => ["required", "string", "email"],
            "password" => [
                "required",
                "string",
                Password::min(8) // Require at least 8 characters
                    ->mixedCase() // Require upper and lower case
                    ->letters() // Require letters
                    ->numbers() // Require numbers
                    ->symbols(), // Require special characters
            ],
            "name"=>"required|string",
            "second_name"=>"required|string"
        ];
    }
}
