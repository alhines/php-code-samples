<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

/*Route::get('/', function () {
    return view('welcome');
});*/

/*
En Laravel, Route::get('/upload', [ImageController::class, 'uploadForm']); registra una ruta que realiza lo siguiente: 
Método HTTP: Responde a una solicitud HTTP de tipo GET.

URI: Cuando se accede a la URL /upload en el navegador (por ejemplo, http://tudominio.com/upload).
Acción: Ejecuta el método uploadForm de la clase ImageController. 

En resumen, la ruta se utiliza para mostrar el formulario de carga de imágenes al usuario. Cuando un usuario visita la URL /upload, Laravel busca la clase ImageController, invoca su método uploadForm y este método típicamente devuelve una vista que contiene el formulario HTML para seleccionar y subir un archivo. 

Flujo típico de carga de archivos

Para completar el proceso de carga, normalmente se requiere una segunda ruta:

Ruta GET: Route::get('/upload', [ImageController::class, 'uploadForm']);

Propósito: Mostrar el formulario de carga.

Ruta POST: Route::post('/upload', [ImageController::class, 'upload']);

Propósito: Procesar la solicitud del formulario. Esta ruta se encarga de recibir los datos del archivo, validarlos y guardarlos en el servidor. 

*/

// si quieres que la raíz muestre el formulario:
//Route::view('/', 'upload');

//Route::get('/upload', [ImageController::class, 'uploadForm']);

Route::get('/', [ImageController::class, 'uploadForm'])->name('image.form');

Route::get('/upload', [ImageController::class, 'uploadForm']);

/*This assigns a unique name to the route, in this case, image.upload. Naming routes is a best practice in Laravel as it allows you to easily reference the route in your application (e.g., in views or redirects) without having to hardcode the URL. This makes your code more maintainable and flexible if you need to change the URL later.
*/

Route::post('/upload', [ImageController::class, 'upload'])->name('image.upload');