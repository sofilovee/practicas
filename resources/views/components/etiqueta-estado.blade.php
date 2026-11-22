@props(['estado'])

@if ($estado === 'disponible')
    <span class="badge bg-success">Disponible</span>
@else
    <span class="badge bg-danger">Agotado</span>
@endif