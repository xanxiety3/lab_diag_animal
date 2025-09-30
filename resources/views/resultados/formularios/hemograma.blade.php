<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado Hemograma</title>
    <style>
        .hemograma-card {
    border-left: 5px solid #e74c3c;
}

.hemograma-card legend {
    font-weight: bold;
    color: #e74c3c;
}

.hemograma-card input, .hemograma-card select, .hemograma-card textarea {
    background-color: #fff5f5;
}body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f6f8;
    margin: 0;
    padding: 20px;
    color: #333;
}

h1, h2 {
    color: #2c3e50;
    margin-bottom: 20px;
}

.btn {
    background-color: #28a745;
    color: white;
    padding: 10px 18px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    font-weight: bold;
    transition: 0.2s;
}

.btn:hover {
    background-color: #218838;
}

.dashboard-btn {
    background-color: #007BFF;
    margin-bottom: 20px;
}

.dashboard-btn:hover {
    background-color: #0056b3;
}

form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Tarjetas uniformes */
.card {
    background-color: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    margin-bottom: 20px;
}

/* Inputs y selects */
input[type="text"], input[type="number"], input[type="date"], select, textarea {
    width: 100%;
    padding: 8px;
    margin-top: 6px;
    margin-bottom: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    box-sizing: border-box;
}

/* Textareas */
textarea {
    resize: vertical;
    min-height: 60px;
}

/* Tablas para Copro Fresco y Bearman */
table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
    border-radius: 8px;
    overflow: hidden;
}

th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

th {
    background-color: #f0f0f0;
}

thead tr {
    background-color: #e9ecef;
}

tr:nth-child(even) {
    background-color: #fafafa;
}


    </style>
</head>
<body>
    <h2>Registrar Resultado Hemograma - Técnica: {{ $tecnica->nombre }}</h2>

    <form action="{{ route('resultados.store_resultado_multiple', ['remisionRecibe' => $remisionRecibeId, 'tecnica' => $tecnica->id]) }}" method="POST">
        @csrf

        @foreach($animales as $animal)
            <fieldset style="margin-bottom:20px; padding:10px; border:1px solid #ccc;">
                <legend>Animal: {{ $animal->nombre }} (ID: {{ $animal->id }})</legend>

                <input type="hidden" name="animal_id[{{ $animal->id }}]" value="{{ $animal->id }}">

                <label>Codigo Interno:
                    <input type="text" name="codigo_interno[{{ $animal->id }}]" value="{{ old('codigo_interno.'.$animal->id, $animal->codigo_interno ?? '') }}">
                </label><br>

                <label>Especie:
                    <input type="text" name="especie[{{ $animal->id }}]" value="{{ old('especie.'.$animal->id, $animal->especie->nombre ?? '') }}">
                </label><br>

                <label>Sexo:
                    <input type="text" name="sexo[{{ $animal->id }}]" value="{{ old('sexo.'.$animal->id, $animal->sexo->descripcion ?? '') }}">
                </label><br>

                <label>HB:
                    <input type="text" name="hb[{{ $animal->id }}]" value="{{ old('hb.'.$animal->id) }}">
                </label><br>

                <label>HTO:
                    <input type="text" name="hto[{{ $animal->id }}]" value="{{ old('hto.'.$animal->id) }}">
                </label><br>

                <label>Leucocitos:
                    <input type="text" name="leucocitos[{{ $animal->id }}]" value="{{ old('leucocitos.'.$animal->id) }}">
                </label><br>

                <label>NEU:
                    <input type="text" name="neu[{{ $animal->id }}]" value="{{ old('neu.'.$animal->id) }}">
                </label><br>

                <label>EOS:
                    <input type="text" name="eos[{{ $animal->id }}]" value="{{ old('eos.'.$animal->id) }}">
                </label><br>

                <label>BAS:
                    <input type="text" name="bas[{{ $animal->id }}]" value="{{ old('bas.'.$animal->id) }}">
                </label><br>

                <label>LIN:
                    <input type="text" name="lin[{{ $animal->id }}]" value="{{ old('lin.'.$animal->id) }}">
                </label><br>

                <label>MON:
                    <input type="text" name="mon[{{ $animal->id }}]" value="{{ old('mon.'.$animal->id) }}">
                </label><br>

                <label>Plaquetas:
                    <input type="text" name="plaquetas[{{ $animal->id }}]" value="{{ old('plaquetas.'.$animal->id) }}">
                </label><br>

                <label>VCM:
                    <input type="text" name="vcm[{{ $animal->id }}]" value="{{ old('vcm.'.$animal->id) }}">
                </label><br>

                <label>HCM:
                    <input type="text" name="hcm[{{ $animal->id }}]" value="{{ old('hcm.'.$animal->id) }}">
                </label><br>

                <label>CHCM:
                    <input type="text" name="chcm[{{ $animal->id }}]" value="{{ old('chcm.'.$animal->id) }}">
                </label><br>

                <label>Hemoparasitos:
                    <input type="text" name="hemoparasitos[{{ $animal->id }}]" value="{{ old('hemoparasitos.'.$animal->id) }}">
                </label><br>

                <label>Observaciones:
                    <textarea name="observaciones[{{ $animal->id }}]">{{ old('observaciones.'.$animal->id) }}</textarea>
                </label><br>

            </fieldset>
        @endforeach

        <button type="submit">Guardar Resultados</button>
    </form>
</body>
</html>
