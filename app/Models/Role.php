<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    
    // Sesuaikan nama tabel jika tidak mengikuti konvensi Laravel
    protected $table = 'roles'; 

    protected $fillable = [
        'role_name',
        'description',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function hasPermission($slug)
    {
        // Superadmin selalu punya akses
        if ($this->role_name === 'Superadmin') {
            return true;
        }
        
        return $this->permissions->contains('slug', $slug);
    }
}