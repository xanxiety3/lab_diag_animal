<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Resultado Hemograma</title>
</head>
<body>
<h2>Registrar Resultado Hemograma</h2>
<form action="/resultados/store/1/1" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <label>Codigo Interno: <input type="text" name="codigo_interno"></label><br>
    <label>Especie: <input type="text" name="especie"></label><br>
    <label>Sexo: <input type="text" name="sexo"></label><br>
    <label>HB: <input type="text" name="hb"></label><br>
    <label>HTO: <input type="text" name="hto"></label><br>
    <label>Leucocitos: <input type="text" name="leucocitos"></label><br>
    <label>NEU: <input type="text" name="neu"></label><br>
    <label>EOS: <input type="text" name="eos"></label><br>
    <label>BAS: <input type="text" name="bas"></label><br>
    <label>LIN: <input type="text" name="lin"></label><br>
    <label>MON: <input type="text" name="mon"></label><br>
    <label>Plaquetas: <input type="text" name="plaquetas"></label><br>
    <label>VCM: <input type="text" name="vcm"></label><br>
    <label>HCM: <input type="text" name="hcm"></label><br>
    <label>CHCM: <input type="text" name="chcm"></label><br>
    <label>Hemoparasitos: <input type="text" name="hemoparasitos"></label><br>
    <label>Observaciones: <textarea name="observaciones"></textarea></label><br>
    <button type="submit">Guardar</button>
</form>
</body>
</html>
