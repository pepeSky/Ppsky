<x-admin-layout>
<x-slot name="content_header"><h1>{{ $activity ? 'Editar actividad' : 'Agregar actividad' }}</h1></x-slot>
@include('admin.mewo._messages')
@if($objectives->isEmpty())<div class="alert alert-warning">No hay objetivos cuánticos disponibles. Puede preparar la actividad y vincularla después de sincronizar la jerarquía de objetivos del proyecto.</div>@endif
<div class="card"><div class="card-body"><form method="POST" action="{{ $activity ? route('admin.activities.update', $activity) : route('admin.activities.store') }}">
@csrf @if($activity) @method('PUT') @endif
<div class="form-group"><label for="project_id">Proyecto</label><select id="project_id" name="project_id" class="form-control" required>
@foreach($projects as $project)<option value="{{ $project->id }}" @selected(old('project_id', $activity?->project_id) == $project->id)>{{ $project->name }}</option>@endforeach
</select></div>
<div class="form-group"><label for="objetive_id">Objetivo cuántico</label><select id="objetive_id" name="objetive_id" class="form-control"><option value="">Pendiente de vinculación</option>
@foreach($objectives as $objective)<option value="{{ $objective->id }}" @selected(old('objetive_id', $activity?->objetives->first()?->id) == $objective->id)>#{{ $objective->id }} — {{ $objective->description }}</option>@endforeach
</select><small>El objetivo debe corresponder al proyecto seleccionado.</small></div>
<div class="form-group"><label for="name">Actividad</label><input id="name" name="name" class="form-control" required maxlength="255" value="{{ old('name', $activity?->name) }}"></div>
<div class="form-group"><label for="description">Descripción</label><textarea id="description" name="description" class="form-control">{{ old('description', $activity?->description) }}</textarea></div>
@php($selectedContexts = array_map('strval', (array) old('context_ids', $activity?->contexts->pluck('id')->all() ?? [])))
<div class="form-group"><label for="context_ids">Contextos participantes</label><select id="context_ids" name="context_ids[]" class="form-control" multiple size="8" required>
@foreach($contexts as $context)<option value="{{ $context->id }}" @selected(in_array((string)$context->id, $selectedContexts, true))>{{ $context->name }}</option>@endforeach
</select><small>Los contextos ampliados incorporan sus componentes simples. Cada contexto conserva su especialidad.</small></div>
@php($selectedProcesses = array_map('strval', (array) old('process_ids', $activity?->mewoProcesses->pluck('id')->all() ?? [])))
<div class="form-group"><label for="process_ids">Procesos que atenderán la actividad</label><select id="process_ids" name="process_ids[]" class="form-control" multiple size="10" required>
@foreach($processes as $process)<option value="{{ $process->id }}" @selected(in_array((string)$process->id, $selectedProcesses, true))>{{ $process->context->name }} — {{ $process->name }}</option>@endforeach
</select><small>Seleccione procesos de los contextos participantes; después podrá registrar sus tareas.</small></div>
<button class="btn btn-primary">Guardar</button><a class="btn btn-secondary" href="{{ route('admin.activities.index') }}">Cancelar</a>
</form></div></div></x-admin-layout>
