<x-admin-layout>
    <x-slot name="content_header">
        <a href="{{ route('admin.applications.edit', $application) }}" class="btn btn-primary float-right">Modificar</a>
        <h1>{{ $application->name }}</h1>
    </x-slot>
    @if (session('info')) <div class="alert alert-success">{{ session('info') }}</div> @endif
    <div class="card"><div class="card-body">
        <dl class="row">
            <dt class="col-sm-3">Sistema</dt><dd class="col-sm-9">{{ $application->system?->identity?->display_name ?? 'Sin sistema asignado' }}</dd>
            <dt class="col-sm-3">Aplicación</dt><dd class="col-sm-9">{{ $application->name }}</dd>
            <dt class="col-sm-3">Plataformas compatibles</dt><dd class="col-sm-9">@forelse (($application->compatible_platforms ?? []) as $platform)<span class="badge badge-info">{{ config('application_platforms.'.$platform, $platform) }}</span> @empty Sin especificar @endforelse</dd>
            <dt class="col-sm-3">Descripción</dt><dd class="col-sm-9">{{ $application->description ?: '—' }}</dd>
            <dt class="col-sm-3">Ubicación</dt><dd class="col-sm-9">{{ $application->location ?: '—' }}</dd>
        </dl>
        <a class="btn btn-secondary" href="{{ route('admin.applications.index') }}">Volver</a>
    </div></div>
    <div class="card"><div class="card-body">
        <h2 class="h5">Modelos</h2>
        @include('admin.applications.model-summary')
    </div></div>
</x-admin-layout>
