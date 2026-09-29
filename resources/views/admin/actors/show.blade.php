@extends('adminlte::page')

@section('title', 'Actor')

@section('content_header')
    <h1>Actor</h1>
@stop

@section('content')

    <div class="card">

        <div class="card-body">

            <dl class="row">

                <dt class="col-sm-3">
                    ID
                </dt>

                <dd class="col-sm-9">
                    {{ $actor->id }}
                </dd>

                <dt class="col-sm-3">
                    Entidad
                </dt>

                <dd class="col-sm-9">
                    {{ $actor->identity->entity->name }}
                </dd>

                <dt class="col-sm-3">
                    Código
                </dt>

                <dd class="col-sm-9">
                    {{ $actor->identity->entity->code }}
                </dd>

                <dt class="col-sm-3">
                    Identificación
                </dt>

                <dd class="col-sm-9">
                    {{ $actor->identity->identification ?? '—' }}
                </dd>

            </dl>

        </div>

        <div class="card-footer">

            <a href="{{ route('admin.actors.edit', $actor) }}"
               class="btn btn-primary">
                Editar
            </a>

            <a href="{{ route('admin.actors.index') }}"
               class="btn btn-secondary">
                Volver
            </a>

        </div>

    </div>

@stop
