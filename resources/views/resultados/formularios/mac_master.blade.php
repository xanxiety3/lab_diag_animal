<style>
    body {
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

<form action="{{ route('resultados.store_resultado_multiple', [$remisionRecibeId, $tecnica->id]) }}" method="POST">

    @csrf

    @foreach($animales as $animal)
        <fieldset>
            <legend>{{ $animal->nombre }} (ID: {{ $animal->id }})</legend>

            <input type="hidden" name="animal_id[{{ $animal->id }}]" value="{{ $animal->id }}">

            <label>Codigo Interno:
                <input type="text" name="codigo_interno[{{ $animal->id }}]" value="{{ old('codigo_interno.'.$animal->id, $animal->codigo_interno ?? '') }}">
            </label><br>

            <label>Cantidad Muestra:
                <input type="text" name="cantidad_muestra[{{ $animal->id }}]" value="{{ old('cantidad_muestra.'.$animal->id) }}">
            </label><br>

            <label>Solución Flotación:
                <input type="text" name="solucion_flotacion[{{ $animal->id }}]" value="{{ old('solucion_flotacion.'.$animal->id) }}">
            </label><br>

            <label>Strongylida C1:
                <input type="text" name="strongylida_c1[{{ $animal->id }}]" value="{{ old('strongylida_c1.'.$animal->id) }}">
            </label><br>

            <label>Strongylida C2:
                <input type="text" name="strongylida_c2[{{ $animal->id }}]" value="{{ old('strongylida_c2.'.$animal->id) }}">
            </label><br>

            <label>Strongylus C1:
                <input type="text" name="strongylus_c1[{{ $animal->id }}]" value="{{ old('strongylus_c1.'.$animal->id) }}">
            </label><br>

            <label>Strongylus C2:
                <input type="text" name="strongylus_c2[{{ $animal->id }}]" value="{{ old('strongylus_c2.'.$animal->id) }}">
            </label><br>

            <label>Moniezia C1:
                <input type="text" name="moniezia_c1[{{ $animal->id }}]" value="{{ old('moniezia_c1.'.$animal->id) }}">
            </label><br>

            <label>Moniezia C2:
                <input type="text" name="moniezia_c2[{{ $animal->id }}]" value="{{ old('moniezia_c2.'.$animal->id) }}">
            </label><br>

            <label>Eimeria C1:
                <input type="text" name="eimeria_c1[{{ $animal->id }}]" value="{{ old('eimeria_c1.'.$animal->id) }}">
            </label><br>

            <label>Eimeria C2:
                <input type="text" name="eimeria_c2[{{ $animal->id }}]" value="{{ old('eimeria_c2.'.$animal->id) }}">
            </label><br>

            <label>Observaciones:
                <textarea name="observaciones[{{ $animal->id }}]">{{ old('observaciones.'.$animal->id) }}</textarea>
            </label><br>
        </fieldset>
    @endforeach

    <button type="submit">Guardar Resultados</button>
</form>
