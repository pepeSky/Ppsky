<x-admin-layout>
    <x-slot name="content_header">
        <a href="{{ route('admin.applications.edit', $application) }}" class="btn btn-primary float-right">Modificar</a>
        <h1>{{ $application->name }}</h1>
    </x-slot>
    @if (session('info')) <div class="alert alert-success">{{ session('info') }}</div> @endif
    <div class="card"><div class="card-body">
        <dl class="row">
            <dt class="col-sm-3">Sistema / usuario</dt><dd class="col-sm-9">{{ $application->user->name }}</dd>
            <dt class="col-sm-3">Descripción</dt><dd class="col-sm-9">{{ $application->description ?: '—' }}</dd>
            <dt class="col-sm-3">Ubicación</dt><dd class="col-sm-9">{{ $application->location ?: '—' }}</dd>
        </dl>
        <a class="btn btn-secondary" href="{{ route('admin.applications.index') }}">Volver</a>
    </div></div>
    <div class="card">
        <div class="card-header"><h2 class="card-title">Modelos utilizados</h2></div>
        <div class="card-body">
            @forelse ($application->models as $model)
                <div class="mb-4">
                    <h3 class="h5">{{ $model->name }}</h3>
                    @if ($model->description) <p>{{ $model->description }}</p> @endif
                    @if ($model->definitions->isNotEmpty())
                        <div class="table-responsive"><table class="table table-sm table-striped">
                            <thead><tr><th>Lenguaje</th><th>Definición</th></tr></thead>
                            <tbody>
                                @foreach ($model->definitions as $definition)
                                    <tr>
                                        <td>{{ $definition->language?->name ?? 'Sin lenguaje' }}@if ($definition->language?->code) ({{ $definition->language->code }}) @endif</td>
                                        <td class="text-break">{{ $definition->definition }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table></div>
                    @else
                        <p class="text-muted">Sin definiciones registradas.</p>
                    @endif
                </div>
            @empty
                <p class="text-muted mb-0">Esta aplicación aún no utiliza modelos registrados.</p>
            @endforelse
        </div>
    </div>
</x-admin-layout>
