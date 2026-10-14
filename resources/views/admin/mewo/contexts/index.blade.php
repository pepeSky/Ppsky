<x-admin-layout>
<x-slot name="content_header"><h1>Integración MeWo</h1><small>Contextos y procesos definidos por el mecanismo</small></x-slot>
<div class="card"><div class="card-body table-responsive"><table class="table"><thead><tr><th>Contexto</th><th>Tipo</th><th>Componentes / Procesos</th></tr></thead><tbody>
@foreach($contexts as $context)<tr><td>{{ $context->name }}</td><td>{{ $context->type }}</td><td>@if($context->type==='ampliado'){{ $context->components->pluck('name')->join(' + ') }}@else @foreach($context->mewoProcesses as $process)<p>{{ $process->name }}: {{ $process->verbs->pluck('infinitive')->join(', ') }}</p>@endforeach @endif</td></tr>@endforeach
</tbody></table></div></div></x-admin-layout>
