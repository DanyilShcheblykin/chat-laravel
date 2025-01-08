<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyRequest extends FormRequest
{
    public function rules()
    {
        return [
            "code" => ["required", "string"],
            "email" => ["required", "string", "email"],
        ];
    }
}
