<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Intervention\Image\Laravel\Facades\Image; // <-- importante

class ImageController extends Controller
{
    public function uploadForm(){
		
		//muestra el formulario
		
		return view('upload');
		
	}
	
	public function upload(Request $request){
		
		//Este método se encarga de manejar la carga (o "upload") de archivos enviados en una petición HTTP. 
		
		//Validacion	
        		
		// 1) Validación
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
        ]);
		
		// 2) Tomamos el archivo subido
       		
		$file = $request->file('image');
		
		// 3) Generamos un nombre único con extensión .webp
		
		//this line of code takes the full original name of an uploaded file and isolates just the name part, discarding any file extension.
		$filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
		
		//significa que se toma la cadena $filename, se convierte a una versión "segura" o "legible para URL" (un "slug") eliminando caracteres especiales, espacios y reemplazándolos con un guion (-),
		
		/*str($filename): Esta es una forma de invocar el método de la clase Illuminate\Support\Str de Laravel. Es similar a decir Str::slug($filename) pero con una sintaxis más "fluida" o en cadena. 
->slug('-'): Este es el método clave que realiza la conversión a un slug.*/ 
		
		$safeName = str($filename)->slug('-'); // "mi foto.jpg" -> "mi-foto"
		
		//se está construyendo un nombre de archivo único para una imagen en formato WebP. 
		
		//time(): Esta función de PHP devuelve la marca de tiempo Unix actual. Es un número entero que representa la cantidad de segundos desde el 1 de enero de 1970. Añadir este valor al nombre del archivo asegura que sea prácticamente único, evitando que un nuevo archivo sobrescriba a uno existente si dos usuarios suben un archivo con el mismo nombre.
					
		$name = $safeName . '-' . time() . '.webp';
		
		// 4) Ruta destino en storage/app/public/images
						
		//significa que se está creando una ruta de archivo para guardar un archivo en la carpeta storage/app/public/images		
		
		/*
		storage_path(): Es una función auxiliar de Laravel que devuelve la ruta absoluta al directorio storage de tu aplicación. La ruta suele ser algo así como /var/www/html/mi-app/storage.
		
'app/public/images/': Es una cadena que se concatena a la ruta base de storage_path(). Esta especifica la subcarpeta dentro de la carpeta storage donde se guardará el archivo. La ruta resultante se verá así: 
/var/www/html/mi-app/storage/app/public/images/.
		
		*/
						
		$dest = storage_path('app/public/images/'.$name);
		
		// 5) Leemos la imagen y la convertimos a WebP (calidad 80)
         
		Image::read($file)->toWebp(80)->save($dest);
		
				
		// 6) Construimos la ruta pública para mostrar en la vista
		
		$publicPath = 'storage/images/'. $name;
		
		// 7) Devolvemos a la vista con mensaje y la URL
		
		/*
		
		Redirige al usuario a la página anterior: El método back() genera una respuesta de redirección que envía al usuario de vuelta a la página donde se encontraba antes de la solicitud HTTP actual.
		
Almacena datos temporales en la sesión (flash data):
->with('success', 'Imagen subida y convertida a WebP'): Este comando almacena un mensaje de éxito en la sesión, bajo la clave 'success'. Este tipo de datos solo estarán disponibles durante la próxima solicitud HTTP y luego se eliminarán automáticamente.

->with('image', $publicPath): De manera similar, se almacena la ruta de la imagen recién subida y convertida (contenida en la variable $publicPath) en la sesión, bajo la clave 'image'.
Permite mostrar estos datos en la vista: En el archivo de vista (Blade), puedes acceder a estos datos temporales para, por ejemplo, mostrar un mensaje de confirmación o el enlace a la imagen, sin que persistan en la sesión en futuras peticiones. 

		*/
		
		return back()->with('success','Imagen subida y convertida a WebP')
		             ->with('image', $publicPath);
		
	}
}
