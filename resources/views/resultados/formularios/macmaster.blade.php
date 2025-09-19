<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Resultado McMaster</title>
</head>
<body>
<h2>Registrar Resultado McMaster</h2>
<form action="/resultados/store/1/1" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <label>Codigo Interno: <input type="text" name="codigo_interno"></label><br>
    <label>Cantidad Muestra: <input type="text" name="cantidad_muestra"></label><br>
    <label>Solución Flotación: <input type="text" name="solucion_flotacion"></label><br>
    <label>Strongylida C1: <input type="text" name="strongylida_c1"></label><br>
    <label>Strongylida C2: <input type="text" name="strongylida_c2"></label><br>
    <label>Strongylus C1: <input type="text" name="strongylus_c1"></label><br>
    <label>Strongylus C2: <input type="text" name="strongylus_c2"></label><br>
    <label>Moniezia C1: <input type="text" name="moniezia_c1"></label><br>
    <label>Moniezia C2: <input type="text" name="moniezia_c2"></label><br>
    <label>Eimeria C1: <input type="text" name="eimeria_c1"></label><br>
    <label>Eimeria C2: <input type="text" name="eimeria_c2"></label><br>
    <label>Observaciones: <textarea name="observaciones"></textarea></label><br>
    <button type="submit">Guardar</button>
</form>
</body>
</html>
