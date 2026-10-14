<x-admin-layout>
<x-slot name="content_header"><h1>{{ $task ? 'Editar tarea' : 'Agregar tarea' }}</h1></x-slot>
@include('admin.mewo._messages')
<p>Actividad: {{ $activity->name }}</p>
<div class="card"><div class="card-body"><form method="POST" action="{{ $task ? route('admin.activities.tasks.update', [$activity, $task]) : route('admin.activities.tasks.store', $activity) }}">
@csrf @if($task) @method('PUT') @endif
<div class="form-group"><label for="name">Tarea</label><input id="name" name="name" class="form-control" maxlength="255" required value="{{ old('name', $task?->name) }}"></div>
<div class="form-group"><label for="description">Descripción / instrucciones de ejecución</label><textarea id="description" name="description" class="form-control">{{ old('description', $task?->description) }}</textarea></div>
<div class="form-group"><label for="mewo_process_id">Proceso responsable</label><select id="mewo_process_id" name="mewo_process_id" class="form-control" required>
@foreach($processes as $process)<option value="{{ $process->id }}" @selected(old('mewo_process_id', $task?->mewo_process_id) == $process->id)>{{ $process->context->name }} — {{ $process->name }}</option>@endforeach
</select></div>
<div class="form-group"><label for="status">Estado</label><select id="status" name="status" class="form-control">@foreach(['pending'=>'Pendiente','in_progress'=>'En ejecución','completed'=>'Finalizada'] as $code=>$label)<option value="{{ $code }}" @selected(old('status', $task?->status ?? 'pending') === $code)>{{ $label }}</option>@endforeach</select></div>
<div class="form-group"><label for="quantity">Cuantía registrada</label><input id="quantity" name="quantity" type="number" min="0" max="9999999999.9999" step="0.0001" required class="form-control" value="{{ old('quantity', $task?->quantity ?? 0) }}"></div>
<div class="form-group"><label for="unit_id">Unidad SyQuAc</label><select id="unit_id" name="unit_id" class="form-control"><option value="">Sin cuantificación</option>@foreach($units as $unit)<option value="{{ $unit->id }}" @selected(old('unit_id', $task?->unit_id) == $unit->id)>{{ $unit->symbol ?? $unit->name }}</option>@endforeach</select></div>
<button class="btn btn-primary">Guardar</button><a class="btn btn-secondary" href="{{ route('admin.activities.show', $activity) }}">Cancelar</a>
</form></div></div></x-admin-layout>
