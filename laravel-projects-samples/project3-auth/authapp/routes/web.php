<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
//use App\Models\Painting;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', function () {
    return view('register');
});

//Para recepcionar valores en un formulario:

Route::post('register', function (Request $request) {
	
	$user = new User;
    $user->email = $request->get('email');
	$user->username = $request->get('username');
	$user->password = Hash::make($request->get('password'));
	$user->save();
	$theEmail = $request->get('email');
    return view('thanks', compact('theEmail'));
});

// Vista de login (tu archivo está en resources/views/auth/login.blade.php)
Route::view('/login', 'auth.login')->name('login');

// Procesar login (usa username; cambia a 'email' si lo tuyo es por email)
Route::post('/login', function (Request $request) {
	
    $credentials = $request->only('username','password'); // o ['email','password']

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        // Si venías de /spotlight, vuelve ahí; si no, cae a /spotlight igualmente
        return redirect()->intended(route('spotlight')); // <-- clave
        // Alternativa (siempre a spotlight, sin intended):
        // return to_route('spotlight');
    }

    return redirect()->route('login')->withErrors(['auth' => 'Credenciales inválidas.']);
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return to_route('login');
})->name('logout');

// RUTA PROTEGIDA: si no estás logueado, redirige a 'login'
//Route::view('/spotlight', 'spotlight')->middleware('auth');

Route::view('/spotlight', 'spotlight')
    ->middleware('auth')
    ->name('spotlight');


