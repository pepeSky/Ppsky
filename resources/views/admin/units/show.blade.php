<x-admin-layout>
    <x-slot name="content_header"><h1>{{ $unit->name }}</h1></x-slot>
    <div class="card"><div class="card-body">
        <dl class="row">
            <dt class="col-sm-3">ID</dt><dd class="col-sm-9">{{ $unit->id }}</dd>
            <dt class="col-sm-3">Instante</dt><dd class="col-sm-9">{{ $unit->created_at?->format('d-m-Y H:i:s') }}</dd>
            <dt class="col-sm-3">Nombre</dt><dd class="col-sm-9">{{ $unit->name }}</dd>
            <dt class="col-sm-3">Magnitud</dt><dd class="col-sm-9">{{ $unit->magnitude }}</dd>
            <dt class="col-sm-3">Sistema</dt><dd class="col-sm-9">{{ $unit->system }}</dd>
            <dt class="col-sm-3">Símbolo</dt><dd class="col-sm-9">{{ $unit->symbol }}</dd>
            <dt class="col-sm-3">Tipo</dt><dd class="col-sm-9">{{ $unit->type }}</dd>
        </dl>
        <a class="btn btn-primary" href="{{ route('admin.units.edit', $unit) }}">Modificar</a>
        <a class="btn btn-secondary" href="{{ route('admin.units.index') }}">Volver</a>
    </div></div>
</x-admin-layout>
