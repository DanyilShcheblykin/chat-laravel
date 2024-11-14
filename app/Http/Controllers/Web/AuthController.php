<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct() {}

    public function registerUser(RegisterRequest $registerRequest)
    {
        $validatedData = $registerRequest->validated();
        $tokenData = $this->generateTempToken($validatedData);

        return $this->ok([ 'token' => $tokenData]);
    }

    private function generateTempToken($validatedData)
    {
        $token = Str::random(60); 
        $expiration = Carbon::now()->addMinutes(30);

        return ['tempToken' => $token, 'expiration' => $expiration];
    }
}
