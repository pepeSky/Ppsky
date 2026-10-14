<x-admin-layout><x-slot name="content_header"><h1>Procesos y verbos MeWo</h1></x-slot>
@include('admin.mewo._messages')
<a class="btn btn-primary mb-3" href="{{ route('admin.mewo.processes.create') }}">Agregar proceso</a>
<div class="card"><div class="card-body table-responsive"><table class="table"><thead><tr><th>Contexto</th><th>Proceso</th><th>Verbos operativos</th><th>Procedimiento</th></tr></thead><tbody>@foreach($processes as $process)<tr><td>{{ $process->context->name }}</td><td><a href="{{ route('admin.mewo.processes.edit', $process) }}">{{ $process->name }}</a></td><td>{{ $process->verbs->pluck('infinitive')->join(', ') }}</td><td>{{ $process->procedure ?? 'Pendiente de definición' }}</td></tr>@endforeach</tbody></table></div></div>
</x-admin-layout>
