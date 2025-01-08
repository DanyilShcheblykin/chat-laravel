<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'verified'
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'verified' => 'boolean',
            'name' => 'string',
            'email' => 'string',
        ];
    }

    public static function create(array $data)
    {
        Log::info('data' , $data);
        return self::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'verified' => false
        ]);
    }

    public function twoFactorToken():HasMany{
        return $this->hasMany(TwoFactorToken::class);
    }
}
