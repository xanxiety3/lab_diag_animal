<h2>🧪 Muestras asociadas (Remisión #{{ $remision->id }})</h2>

@if ($muestras->isNotEmpty())
    <table>
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Cantidad</th>
                <th>Refrigeración</th>
                <th>Observaciones</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($muestras as $muestra)
                <tr>
                    <td>{{ $muestra->nombre }}</td>
                    <td>{{ $muestra->pivot->cantidad_muestra ?? '-' }}</td>
                    <td>{{ $muestra->pivot->refrigeracion ? 'Sí' : 'No' }}</td>
                    <td>{{ $muestra->pivot->observaciones ?? '-' }}</td>
                   <td>
                       @if($remisionRecibe && $remisionRecibe->tecnicas->isNotEmpty())
    <ul>
        @foreach($remisionRecibe->tecnicas as $tecnica)
            <li>{{ $tecnica->nombre }}</li>
        @endforeach
    </ul>
@else
    <p>No hay técnicas asociadas</p>
@endif

                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No hay muestras registradas en esta remisión.</p>
@endif
