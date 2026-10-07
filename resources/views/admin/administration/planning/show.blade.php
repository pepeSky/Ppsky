<x-admin-layout>

    <x-slot name="content_header">
        <div>
            <a href="{{ route('admin.planning.index') }}"
               class="btn btn-sm btn-default float-right">
                Volver a Planning
            </a>

            <h1 class="mb-0">{{ $plan->name }}</h1>

            <small class="text-muted">
                {{ $plan->project->system->identity->entity->name }}
                ·
                {{ $plan->project->name }}
            </small>
        </div>
    </x-slot>

    <div class="container-fluid">

        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    Planificación
                </h3>

                <div class="card-tools">
                    <a href="{{ route('admin.planning.edit', $plan) }}"
                       class="btn btn-sm btn-primary">
                        <i class="fas fa-edit mr-1"></i>
                        Editar
                    </a>
                </div>
            </div>

            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-2">Plan</dt>
                    <dd class="col-sm-10">{{ $plan->name }}</dd>

                    <dt class="col-sm-2">Tipo</dt>
                    <dd class="col-sm-10">
                        @switch($plan->type)
                            @case('strategic')
                                Estratégico
                                @break

                            @case('tactical')
                                Táctico
                                @break

                            @case('operational')
                                Operativo
                                @break

                            @default
                                {{ $plan->type }}
                        @endswitch
                    </dd>

                    <dt class="col-sm-2">Inicio</dt>
                    <dd class="col-sm-10">
                        {{ $plan->starts_at?->format('d-m-Y') }}
                    </dd>

                    <dt class="col-sm-2">Término</dt>
                    <dd class="col-sm-10">
                        {{ $plan->ends_at?->format('d-m-Y') }}
                    </dd>

                    <dt class="col-sm-2">Descripción</dt>
                    <dd class="col-sm-10">
                        {{ $plan->description }}
                    </dd>
                </dl>
            </div>
        </div>

        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title">
                    Metas de Vida
                </h3>

                <div class="card-tools">
                    <a href="{{ route('admin.planning.goals.create', $plan) }}"
                       class="btn btn-sm btn-success">
                        <i class="fas fa-plus mr-1"></i>
                        Nueva Meta
                    </a>
                </div>
            </div>

            <div class="card-body">

                @error('goal')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                @enderror

                @forelse ($plan->goals as $goal)

                    <div class="{{ ! $loop->last ? 'border-bottom mb-3 pb-3' : '' }}">

                        <div class="d-flex justify-content-between align-items-start">

                            <div class="pr-3">
                                <h5>{{ $goal->name }}</h5>

                                <p class="mb-0">
                                    {{ $goal->description }}
                                </p>
                            </div>

                            <div class="btn-group btn-group-sm">

                                <a href="{{ route(
                                    'admin.planning.goals.edit',
                                    [$plan, $goal]
                                ) }}"
                                   class="btn btn-default"
                                   title="Editar Meta">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form method="POST"
                                      action="{{ route(
                                          'admin.planning.goals.destroy',
                                          [$plan, $goal]
                                      ) }}">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-danger"
                                            title="Eliminar Meta"
                                            onclick="return confirm('¿Eliminar esta Meta de Vida?')">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                @empty

                    <p class="text-muted mb-0">
                        No existen metas registradas.
                    </p>

                @endforelse

            </div>
        </div>

        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">
                    Objetivos
                </h3>
            </div>

            <div class="card-body">

                @php
                    $general = $plan->objetives
                        ->firstWhere('is_general', true);

                    $rootObjetives = $plan->objetives
                        ->where('is_general', false)
                        ->whereNull('parent_id');
                @endphp

                @if ($general)
                    <div class="mb-4">
                        <h5>Objetivo General</h5>

                        <p class="mb-0">
                            {{ $general->description }}
                        </p>
                    </div>
                @endif

                @forelse ($rootObjetives as $objetive)

                    <div class="{{ ! $loop->last
                        ? 'border-top pt-3 mb-4'
                        : 'border-top pt-3' }}">

                        <div class="d-flex justify-content-between">
                            <h5>
                                Objetivo N.º {{ $loop->iteration }}
                            </h5>

                            @if ($objetive->goal)
                                <span class="badge badge-secondary">
                                    {{ $objetive->goal->name }}
                                </span>
                            @endif
                        </div>

                        <p>
                            {{ $objetive->description }}
                        </p>

                        @if ($objetive->children->isNotEmpty())
                            <h6>Objetivos específicos</h6>

                            <ol class="mb-0">
                                @foreach ($objetive->children as $child)
                                    <li class="mb-2">
                                        {{ $child->description }}
                                    </li>
                                @endforeach
                            </ol>
                        @endif

                    </div>

                @empty

                    <p class="text-muted mb-0">
                        No existen objetivos asociados al plan.
                    </p>

                @endforelse

            </div>
        </div>

    </div>

</x-admin-layout>
