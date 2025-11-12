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
}