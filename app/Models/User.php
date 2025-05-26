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
        'email',
        'password',
        'first_name',
        'last_name',
        'phone_number',
        'address',
        'role',
        'is_admin',
        'is_super_admin', // Added super admin field
        'city',
        'state',
        'postal_code',
        'country',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'is_super_admin' => 'boolean', // Added super admin cast
    ];

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function searches()
    {
        return $this->hasMany(Search::class);
    }

    public function passwordResets()
    {
        return $this->hasMany(PasswordReset::class);
    }

    public function isAdmin()
    {
        // Check both fields for compatibility with old and new code
        return $this->is_admin || $this->role === 'admin';
    }
    
    public function isSuperAdmin()
    {
        return $this->is_super_admin;
    }
}