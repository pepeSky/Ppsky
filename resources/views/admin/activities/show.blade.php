<x-admin-layout>
<x-slot name="content_header"><h1>{{ $activity->name }}</h1></x-slot>
@include('admin.mewo._messages')
<p>Proyecto: {{ $activity->project->name }}</p><p>{{ $activity->description }}</p>
<p>Objetivo: @forelse($activity->objetives as $objective)#{{ $objective->id }} {{ $objective->description }} @empty Pendiente de vinculación @endforelse</p>
<a class="btn btn-primary mb-3" href="{{ route('admin.activities.edit', $activity) }}">Editar actividad</a>
<a class="btn btn-success mb-3" href="{{ route('admin.activities.tasks.create', $activity) }}">Agregar tarea</a>
<div class="card"><div class="card-header">Contextos y procesos participantes</div><div class="card-body">
<p>{{ $activity->contexts->pluck('name')->join(', ') }}</p>
@foreach($activity->mewoProcesses as $process)<p><strong>{{ $process->context->name }}:</strong> {{ $process->name }}</p>@endforeach
</div></div>
<div class="card"><div class="card-header">Tareas</div><div class="card-body table-responsive"><table class="table"><thead><tr><th>Tarea</th><th>Contexto / Proceso</th><th>Estado</th><th>Cuantía</th><th>Acciones</th></tr></thead><tbody>
@forelse($activity->tasks as $task)<tr><td>{{ $task->name }}</td><td>{{ $task->context->name }} / {{ $task->mewoProcess?->name ?? 'Proceso pendiente' }}</td><td>{{ ['pending'=>'Pendiente','in_progress'=>'En ejecución','completed'=>'Finalizada'][$task->status] ?? $task->status }}</td><td>{{ $task->quantity }} {{ $task->unit?->symbol ?? $task->unit?->name }}</td><td><a href="{{ route('admin.activities.tasks.edit', [$activity, $task]) }}">Editar</a>
@if($task->status === 'pending' && (float)$task->quantity === 0)<form method="POST" action="{{ route('admin.activities.tasks.destroy', [$activity, $task]) }}">@csrf @method('DELETE')<button class="btn btn-link text-danger">Eliminar borrador</button></form>@endif
</td></tr>@empty<tr><td colspan="5">No hay tareas.</td></tr>@endforelse</tbody></table>
</div></div>
<div class="card"><div class="card-header">Cuantificación de la actividad</div><div class="card-body">
@forelse($quantities as $quantity)<p>{{ $quantity->total }} {{ $quantity->unit?->symbol ?? $quantity->unit?->name }}</p>@empty<p>No hay cuantías registradas.</p>@endforelse
<p>Cada tarea se contabiliza una vez por unidad. Las cantidades expresan trabajo registrado; el cumplimiento del objetivo requiere su propia verificación.</p>
</div></div>
@if($activity->tasks->isEmpty())<form method="POST" action="{{ route('admin.activities.destroy', $activity) }}">@csrf @method('DELETE')<button class="btn btn-danger">Eliminar actividad sin tareas</button></form>@endif
</x-admin-layout>
