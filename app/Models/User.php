<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'two_factor_code',
        'two_factor_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_code',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'two_factor_expires_at' => 'datetime',
    ];

    public function generateTwoFactorCode(): string
    {
        $code = (string) random_int(100000, 999999);
        $this->forceFill([
            'two_factor_code' => $code,
            'two_factor_expires_at' => now()->addMinutes(10),
        ])->save();

        return $code;
    }

    public function resetTwoFactorCode(): void
    {
        $this->forceFill([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ])->save();
    }

    public function isTwoFactorCodeValid(string $code): bool
    {
        if (empty($this->two_factor_code) || empty($this->two_factor_expires_at)) {
            return false;
        }

        if (now()->gt($this->two_factor_expires_at)) {
            return false;
        }

        return hash_equals((string) $this->two_factor_code, trim($code));
    }

    public function getMaskedEmailAttribute(): string
    {
        $parts = explode('@', $this->email);
        if (count($parts) !== 2) return $this->email;
        $name = $parts[0];
        $domain = $parts[1];
        $len = strlen($name);
        if ($len <= 2) {
            $maskedName = substr($name, 0, 1) . '*';
        } else {
            $maskedName = substr($name, 0, 1) . str_repeat('*', max(3, $len - 2)) . substr($name, -1);
        }
        return $maskedName . '@' . $domain;
    }

    public function assignedEnquiries()
    {
        return $this->hasMany(Enquiry::class, 'assigned_staff_id');
    }
}