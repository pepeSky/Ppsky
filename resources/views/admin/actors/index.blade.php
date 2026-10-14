<x-admin-layout>
<x-slot name="content_header"><h1>Actores</h1><p class="mb-0">Sistema: {{ $system->identity->display_name }}</p></x-slot>
@include('admin.actors.messages')
<div class="card"><div class="card-header d-flex flex-wrap" style="gap:8px">
@if($access->allows(request(),$system,'actors.create'))<a href="{{ route('admin.actors.create') }}" class="btn btn-primary">Registrar actor</a><a href="{{ route('admin.actors.import') }}" class="btn btn-outline-primary">Importar actor</a>@endif
@if($access->allows(request(),$system,'access.manage'))<a href="{{ route('admin.actor-access.index') }}" class="btn btn-outline-secondary">Roles y permisos</a>@endif
@if($access->allows(request(),$system,'identities.manage'))<a href="{{ route('admin.identities.index') }}" class="btn btn-outline-secondary">Identidades</a>@endif
</div><div class="card-body table-responsive"><table class="table table-striped"><thead><tr><th>ID</th><th>Nombre del actor</th><th>Cohesiones</th><th></th></tr></thead><tbody>
@forelse($actors as $actor)<tr><td>{{ $actor->id }}</td><td>{{ $actor->display_name }}@if(!$actor->system_id)<small class="d-block text-muted">Sistema pendiente de asignación</small>@endif @if($actor->archived_at)<small class="d-block text-muted">Archivado</small>@endif</td><td>@forelse($actor->cohesions as $cohesion)<div>{{ $cohesion->name }}</div>@empty — @endforelse</td><td class="text-nowrap text-right"><a class="btn btn-info btn-sm" href="{{ route('admin.actors.show',$actor) }}">Ver</a>
@if(!$actor->archived_at && $access->allows(request(),$system,'actors.edit'))<a class="btn btn-primary btn-sm" href="{{ route('admin.actors.edit',$actor) }}">Editar</a>@endif
</td></tr>@empty<tr><td colspan="4">No hay actores registrados en este sistema.</td></tr>@endforelse
</tbody></table>{{ $actors->links() }}</div></div></x-admin-layout>
