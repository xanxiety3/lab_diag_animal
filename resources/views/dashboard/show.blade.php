
<div class="container">
    <h1>Detalle de la Remisión #{{ $remision->id }}</h1>

    <h3>Datos generales</h3>
    <ul>
        <li><strong>Fecha:</strong> {{ $remision->fecha->format('d/m/Y H:i') }}</li>
        <li><strong>Estado:</strong> {{ ucfirst($remision->estado) }}</li>
    </ul>

    <h3>Propietario</h3>
    <ul>
        <li><strong>Nombre:</strong> {{ $remision->persona?->nombres }} {{ $remision->persona?->apellidos }}</li>
        <li><strong>Documento:</strong> {{ $remision->persona?->numero_documento }}</li>
        <li><strong>Direcciones:</strong></li>
        <ul>
            @foreach ($remision->persona?->direcciones as $direccion)
                <li>{{ $direccion->direccion_detallada }}</li>
            @endforeach
        </ul>
        <li><strong>Teléfono:</strong> {{ $remision->persona?->telefono }}</li>
    </ul>

    <h3>Animales</h3>
        @foreach ($remision->persona?->animales as $animal)
        <p>
            <li><strong>Nombre:</strong> {{ $animal->nombre }} </li>
             <li><strong>Especie:</strong> {{ $animal->especie?->nombre }}</li> 
             <li><strong>Raza:</strong> {{ $animal->raza?->nombre }}</li> 
        </p>
        @endforeach

    <h3>Muestras</h3>
    @forelse($remision->tiposMuestra as $muestra)
        <p>
            {{ $muestra->pivot->cantidad_muestra }} x {{ $muestra->nombre }}
            @if($muestra->pivot->refrigeracion)
                (Refrigerada)
            @endif
            @if($muestra->pivot->observaciones)
                - {{ $muestra->pivot->observaciones }}
            @endif
        </p>
    @empty
        <p>No se registraron muestras para esta remisión.</p>
    @endforelse

    <h3>Observaciones</h3>
    <p>{{ $remision->observaciones ?? 'Ninguna' }}</p>

    <div class="acciones">
@if(!$remision->rechazada && $remision->remision_muestra_recibes->isNotEmpty())
    <a href="{{ route('resultados.create', $remision->remision_muestra_recibe->first()->id) }}" class="btn-registrar">
        ➕ Registrar Resultados
    </a>
@else
    <span class="texto-rechazada">Muestra rechazada, no se pueden registrar resultados.</span>
@endif


</div>

</div>

