<x-admin-layout>

    <x-slot name="content_header">
        <a href="{{ route('admin.interactions.create') }}"
           class="btn btn-primary float-right">
            Agregar Interacción
        </a>

        <h1>Interacciones</h1>

        <p>
            Relaciones recíprocas establecidas entre actores,
            clasificadas según su forma de cohesión.
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
                        <th>Actor</th>
                        <th></th>
                        <th>Actor</th>
                        <th>Cohesión</th>
                        <th>Símbolo</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($interactions as $interaction)
                        <tr>
                            <td>{{ $interaction->id }}</td>

                            <td>
                                {{ $interaction->actorA->identity->entity->name }}
                            </td>

                            <td class="text-center">
                                ↔
                            </td>

                            <td>
                                {{ $interaction->actorB->identity->entity->name }}
                            </td>

                            <td>
                                {{ $interaction->cohesion->name }}
                            </td>

                            <td>
                                {{ $interaction->cohesion->symbol ?? '—' }}
                            </td>

                            <td class="text-right">
                                <a href="{{ route('admin.interactions.show', $interaction) }}"
                                   class="btn btn-sm btn-info">
                                    Ver
                                </a>

                                <a href="{{ route('admin.interactions.edit', $interaction) }}"
                                   class="btn btn-sm btn-warning">
                                    Editar
                                </a>

                                <form action="{{ route('admin.interactions.destroy', $interaction) }}"
                                      method="POST"
                                      class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('¿Eliminar esta interacción?')">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                No existen interacciones registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

    {{ $interactions->links() }}

</x-admin-layout>
