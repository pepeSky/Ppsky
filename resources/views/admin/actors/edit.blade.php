@extends('adminlte::page')

@section('title', 'Editar actor')

@section('content_header')
    <h1>Editar actor</h1>
@stop

@section('content')

    <div class="card">

        <div class="card-body">

            <form action="{{ route('admin.actors.update', $actor) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="form-group">

                    <label for="identity_id">
                        Identidad
                    </label>

                    <select name="identity_id"
                            id="identity_id"
                            class="form-control">

                        @foreach ($identities as $identity)

                            <option value="{{ $identity->id }}"
                                @selected(
                                    old(
                                        'identity_id',
                                        $actor->identity_id
                                    ) == $identity->id
                                )>

                                {{ $identity->entity->name }}

                                @if ($identity->identification)
                                    — {{ $identity->identification }}
                                @endif

                            </option>

                        @endforeach

                    </select>

                    @error('identity_id')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror

                </div>

                <button type="submit"
                        class="btn btn-primary">
                    Actualizar
                </button>

                <a href="{{ route('admin.actors.index') }}"
                   class="btn btn-secondary">
                    Cancelar
                </a>

            </form>

        </div>

    </div>

@stop
