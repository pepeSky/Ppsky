
<x-admin-layout>

    <x-slot name="content_header">
        @if($canManage)
        <a href="{{ route('admin.identities.create') }}"
           class="btn btn-primary float-right">
            Agregar Identidad
        </a>
        @endif

        <h1>Identidades</h1>

        <p>
            Entidades identificadas y reconocidas por el usuario
            dentro de su sistema de información.
        </p>
    </x-slot>

    @if (session('info'))
        <div class="alert alert-success">
            {{ session('info') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body table-responsive p-0">

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Entidad</th>
                        <th>Código</th>
                        <th>Naturaleza</th>
                        <th>Datos de contacto</th>
                        <th>Información personal</th>
                        <th>Actor</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($identities as $identity)
                        <tr>
                            <td>{{ $identity->id }}</td>
                            <td>{{ $identity->entity->name }}</td>
                            <td>{{ $identity->entity->code ?? '—' }}</td>
                            <td>{{ $identity->entity->nature->name }}</td>
                            @php($completion = app(\App\Services\MeWo\IdentityCompleteness::class)->percentages($identity))
                            <td>@include('admin.identities.completion-bar',['percentage'=>$completion['contact'],'label'=>'Datos de contacto'])</td>
                            <td>@include('admin.identities.completion-bar',['percentage'=>$completion['personal'],'label'=>'Información personal'])</td>

                            <td>
                                @if ($identity->actors->isNotEmpty())
                                    <span class="badge badge-success">
                                        {{ $identity->actors->count() }}
                                    </span>
                                @else
                                    —
                                @endif
                            </td>

                            <td class="text-right">
                                <a href="{{ route('admin.identities.show', $identity) }}"
                                   class="btn btn-sm btn-info">
                                    Ver
                                </a>

                                @if($canManage)
                                <a href="{{ route('admin.identities.edit', $identity) }}"
                                   class="btn btn-sm btn-warning">
                                    Editar
                                </a>

                                <form action="{{ route('admin.identities.destroy', $identity) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('¿Eliminar esta identidad?')">
                                        Eliminar
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                No existen identidades registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

    {{ $identities->links() }}

</x-admin-layout>
