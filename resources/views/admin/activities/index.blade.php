<x-admin-layout>
<x-slot name="content_header"><h1>Actividades MeWo</h1></x-slot>
@include('admin.mewo._messages')
<a class="btn btn-primary mb-3" href="{{ route('admin.activities.create') }}">Agregar actividad</a>
<div class="card"><div class="card-body table-responsive"><table class="table">
<thead><tr><th>Actividad</th><th>Proyecto</th><th>Contextos participantes</th><th>Tareas</th></tr></thead><tbody>
@forelse($activities as $activity)<tr><td><a href="{{ route('admin.activities.show', $activity) }}">{{ $activity->name }}</a></td><td>{{ $activity->project->name }}</td><td>{{ $activity->contexts->pluck('name')->join(', ') }}</td><td>{{ $activity->tasks_count }}</td></tr>
@empty<tr><td colspan="4">No hay actividades registradas.</td></tr>@endforelse
</tbody></table>{{ $activities->links() }}</div></div>
</x-admin-layout>
