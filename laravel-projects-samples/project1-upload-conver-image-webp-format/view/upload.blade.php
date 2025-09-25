<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Subir Imagen WebP</title>
</head>
<body>
  <h1>Subir Imagen y Convertir a WebP</h1>

  @if ($errors->any())
    <div style="color:red;">
      <ul>
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @if (session('success'))
    <p style="color:green;">{{ session('success') }}</p>
    <img src="{{ asset(session('image')) }}" alt="Imagen convertida" width="320">
  @endif

  <form action="{{ route('image.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="image" accept="image/*" required>
    <button type="submit">Subir y Convertir</button>
  </form>
</body>
</html>