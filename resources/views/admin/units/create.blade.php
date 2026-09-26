<x-admin-layout>
    <x-slot name="content_header"><h1>Agregar unidad</h1></x-slot>
    <div class="card"><div class="card-body">
        <form action="{{ route('admin.units.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nombre</label>
                <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required maxlength="255">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="symbol">Símbolo</label>
                <input id="symbol" name="symbol" class="form-control @error('symbol') is-invalid @enderror" value="{{ old('symbol') }}" required maxlength="255">
                @error('symbol') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            @foreach (['magnitude' => 'Magnitud', 'system' => 'Sistema', 'type' => 'Tipo'] as $field => $label)
                <div class="form-group">
                    <label for="{{ $field }}">{{ $label }}</label>
                    <input id="{{ $field }}" name="{{ $field }}" class="form-control @error($field) is-invalid @enderror" value="{{ old($field) }}" maxlength="255">
                    @error($field) <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            @endforeach
            <button class="btn btn-primary" type="submit">Guardar</button>
            <a class="btn btn-secondary" href="{{ route('admin.units.index') }}">Cancelar</a>
        </form>
    </div></div>
</x-admin-layout>
