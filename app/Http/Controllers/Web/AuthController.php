<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\VerifyRequest;
use App\Models\TwoFactorToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct() {}

    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
    
        // Attempt to authenticate the user
        if (!Auth::attempt($validatedData)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    
        // Get the authenticated user
        $user = Auth::user();
    
        // Check if the user is verified
        if (!$user->verified) {
            return response()->json(['message' => 'User is not verified'], 403);
        }
    
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ]);
    }
    
    public function registerUser(RegisterRequest $registerRequest)
    {
        $validatedData = $registerRequest->validated();
        $tokenData = $this->generateTempToken($validatedData);
        $createdUser = User::create($validatedData);

        TwoFactorToken::create([
            'user_id' => $createdUser->id,
            'token' => $tokenData['tempToken'],
            'expires_at' => $tokenData['expiration']
        ]);

        // Return the temporary token for the user
        return $this->ok(['temp_token' => $tokenData['tempToken']]);
    }

    private function generateTempToken($validatedData)
    {
        $token = Str::random(60);
        $expiration = Carbon::now()->addMinutes(30);
        return ['tempToken' => $token, 'expiration' => $expiration];
    }

    public function verifyUser(VerifyRequest $verifyRequest)
    {
        $code = $verifyRequest->code;

        $user = User::where('email', $verifyRequest->email)
            ->with(['twoFactorToken' => function ($query) use ($code) {
                $query->where('token', $code);
            }])
            ->first();

        if (!$user || $user->twoFactorToken->isEmpty()) {
            return $this->fail("Invalid email or verification code.", 400);
        }

        $user->verified = true;
        $user->save();

        return $this->ok(['success']);
    }
}
