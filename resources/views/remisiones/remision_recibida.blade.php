<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
 <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f4f8;
            padding: 30px;
        }

        form {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        }

        h4 {
            font-size: 20px;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        label {
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
            color: #444;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 16px;
            transition: border 0.3s ease;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
        }

        .tipo-muestra {
            border: 1px solid #dce1e7;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            background-color: #f9fafa;
        }

        .tipo-muestra label {
            margin-top: 10px;
        }

        .tipo-muestra input[type="checkbox"] {
            margin-right: 8px;
            transform: scale(1.2);
        }

        button {
            background-color: #3498db;
            color: white;
            padding: 14px 22px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2d7fbf;
        }
    </style>
<body>
   <h2>Registrar muestra recibida</h2>

<form action="{{ route('remisiones.recibida') }}" method="POST">
    @csrf

    <input type="hidden" name="muestra_enviada_id" value="{{ $remision->id }}">
<p><strong>Remisión:</strong> #{{ $remision->id }} del {{ $remision->fecha }}</p>




<label>Selecciona técnicas:</label>
<div style="display: flex; flex-wrap: wrap; gap: 10px;">
    @foreach($tecnicas as $tecnica)
        <label style="border: 1px solid #ccc; padding: 8px; border-radius: 6px; background: #f9f9f9;">
            <input type="checkbox" name="tecnicas[]" value="{{ $tecnica->id }}">
            {{ $tecnica->nombre }}
        </label>
    @endforeach
</div>

    <button type="submit">Guardar</button>
</form>


</body>
</html>