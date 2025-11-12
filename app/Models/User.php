<?php

namespace App\Models;
use App\Models\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id', // Pastikan ini ada
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationship ke tabel roles.
     */
    public function roleModel()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Accessor untuk mendapatkan role name (misalnya 'admin', 'dokter').
     * Ini yang akan dipanggil oleh Auth::user()->role di blade.
     */
    protected function role(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $this->roleModel ? strtolower($this->roleModel->role_name) : 'pasien', // Default ke pasien jika tidak ada
        );
    }

    // Pastikan Anda juga memiliki Model Role.php
}