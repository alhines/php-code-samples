<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EstudianteController;

Route::get   ('/estudiantes',                [EstudianteController::class, 'index']);
Route::post  ('/estudiantes',                [EstudianteController::class, 'store']);
Route::match (['put','patch'], '/estudiantes/{estudiante}', [EstudianteController::class, 'update']);
Route::delete('/estudiantes/{estudiante}',   [EstudianteController::class, 'destroy']);
