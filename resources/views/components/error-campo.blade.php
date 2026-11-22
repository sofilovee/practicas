@props(['campo'])

@error($campo)
    <div class="text-danger small">{{ $message }}</div>
@enderror