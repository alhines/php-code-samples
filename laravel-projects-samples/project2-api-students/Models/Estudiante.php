<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $table = 'estudiantes'; // <- explícitalo para evitar dudas
    protected $fillable = ['name','email','password'];
    protected $hidden = ['password'];
}
