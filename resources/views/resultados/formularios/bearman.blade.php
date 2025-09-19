<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Resultado Bearman</title>
</head>
<body>
<h2>Registrar Resultado Bearman</h2>
<form action="/resultados/store/1/1" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <label>Codigo Interno: <input type="text" name="codigo_interno"></label><br>
    <label>Codigo Solicitud: <input type="text" name="codigo_solicitud"></label><br>
    <label>Fecha Análisis: <input type="date" name="fecha_analisis"></label><br>
    <label>Cantidad Muestra: <input type="text" name="cantidad_muestra"></label><br>
    <label>Larvas: <input type="text" name="larvas"></label><br>
    <label>Observaciones: <textarea name="observaciones"></textarea></label><br>
    <button type="submit">Guardar</button>
</form>
</body>
</html>
