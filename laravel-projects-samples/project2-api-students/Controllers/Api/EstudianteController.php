<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EstudianteController extends Controller
{
    public function index()
    {
        return Estudiante::orderByDesc('id')->get();
    }

    /*public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255','unique:estudiantes,email'],
            'password' => ['required','string','min:6'],
        ]);
        $data['password'] = Hash::make($data['password']);
        return response()->json(Estudiante::create($data), 201);
    }*/
	
	public function store(\Illuminate\Http\Request $request)
{
    // 1) Mira exactamente qué llega
    \Log::info('POST /estudiantes payload', $request->all());

    // 2) Valida y crea
    try {
        $data = $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255','unique:estudiantes,email'],
            'password' => ['required','string','min:6'],
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'ok' => false,
            'error' => 'VALIDATION',
            'messages' => $e->errors(),
            'recibido' => $request->all(),
        ], 422);
    }

    $data['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);

    $estudiante = \App\Models\Estudiante::create($data);

    return response()->json([
        'ok' => true,
        'created' => $estudiante,
        'recibido' => $request->all(),
        'db' => \DB::connection()->getDatabaseName(), // ver a qué BD pegó
    ], 201);
}

    public function update(Request $request, Estudiante $estudiante)
    {
        $data = $request->validate([
            'name'     => ['sometimes','string','max:255'],
            'email'    => ['sometimes','email','max:255', Rule::unique('estudiantes','email')->ignore($estudiante->id)],
            'password' => ['sometimes','string','min:6'],
        ]);
        if (isset($data['password'])) $data['password'] = Hash::make($data['password']);
        $estudiante->fill($data)->save();
        return $estudiante;
    }

    public function destroy(Estudiante $estudiante)
    {
        $estudiante->delete();
        return response()->noContent();
    }
}
