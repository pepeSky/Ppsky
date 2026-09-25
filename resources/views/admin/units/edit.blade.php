<x-admin-layout>
    <x-slot name="content_header"><h1>Modificar unidad</h1></x-slot>
    <div class="card"><div class="card-body">
        <form action="{{ route('admin.units.update', $unit) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label for="name">Nombre</label>
                <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $unit->name) }}" required maxlength="255">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label for="symbol">Símbolo</label>
                <input id="symbol" name="symbol" class="form-control @error('symbol') is-invalid @enderror" value="{{ old('symbol', $unit->symbol) }}" required maxlength="255">
                @error('symbol') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <button class="btn btn-primary" type="submit">Guardar</button>
            <a class="btn btn-secondary" href="{{ route('admin.units.index') }}">Cancelar</a>
        </form>
    </div></div>
</x-admin-layout>
