@extends('adminlte::page')

@section('title', 'UF - SyQuAc')

@section('content_header')
    <h1>Unidad de Fomento</h1>
@stop

@section('content')

    {{-- Mensaje de sincronización exitosa --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="close"
                data-dismiss="alert"
                aria-label="Cerrar"
            >
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif


    {{-- Errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <h5>
                <i class="icon fas fa-ban"></i>
                No fue posible realizar la operación
            </h5>

            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Sincronización con Banco Central --}}
    <div class="card card-outline card-primary">

        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-sync-alt mr-1"></i>
                Sincronización
            </h3>
        </div>

        <form
            method="POST"
            action="{{ route('admin.syquac.uf.sincronizar') }}"
        >
            @csrf

            <div class="card-body">

                <div class="row">

                    {{-- Fecha inicial --}}
                    <div class="col-md-5">

                        <div class="form-group">

                            <label for="fecha_inicio">
                                Fecha inicial
                            </label>

                            <input
                                type="date"
                                id="fecha_inicio"
                                name="fecha_inicio"
                                class="form-control @error('fecha_inicio') is-invalid @enderror"
                                value="{{ old('fecha_inicio', now()->startOfMonth()->format('Y-m-d')) }}"
                                required
                            >

                            @error('fecha_inicio')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>

                    </div>


                    {{-- Fecha final --}}
                    <div class="col-md-5">

                        <div class="form-group">

                            <label for="fecha_fin">
                                Fecha final
                            </label>

                            <input
                                type="date"
                                id="fecha_fin"
                                name="fecha_fin"
                                class="form-control @error('fecha_fin') is-invalid @enderror"
                                value="{{ old('fecha_fin', now()->format('Y-m-d')) }}"
                                required
                            >

                            @error('fecha_fin')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>

                    </div>


                    {{-- Botón --}}
                    <div class="col-md-2">

                        <div class="form-group">

                            <label>&nbsp;</label>

                            <button
                                type="submit"
                                class="btn btn-primary btn-block"
                            >
                                <i class="fas fa-sync-alt mr-1"></i>
                                Sincronizar
                            </button>

                        </div>

                    </div>

                </div>

            </div>

            <div class="card-footer text-muted">

                <i class="fas fa-university mr-1"></i>

                Fuente:
                Banco Central de Chile —
                Base de Datos Estadísticos (BDE)

            </div>

        </form>

    </div>


    {{-- Valores almacenados --}}
    <div class="card">

        <div class="card-header">

            <h3 class="card-title">
                <i class="fas fa-table mr-1"></i>
                Valores UF
            </h3>

            <div class="card-tools">

                <span class="badge badge-primary">
                    {{ $ufs->total() }} registros
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-striped table-hover mb-0">

                    <thead>

                        <tr>
                            <th>Fecha</th>
                            <th class="text-right">Valor</th>
                            <th>Código</th>
                            <th>Fuente</th>
                            <th>Consultado</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($ufs as $uf)

                            <tr>

                                <td>
                                    {{ $uf->fecha->format('d-m-Y') }}
                                </td>

                                <td class="text-right">

                                    <strong>
                                        $
                                        {{ number_format(
                                            (float) $uf->valor,
                                            2,
                                            ',',
                                            '.'
                                        ) }}
                                    </strong>

                                </td>

                                <td>
                                    <span class="badge badge-success">
                                        {{ $uf->codigo }}
                                    </span>
                                </td>

                                <td>
                                    {{ $uf->fuente }}
                                </td>

                                <td>

                                    @if ($uf->consultado_at)

                                        {{ $uf->consultado_at->format(
                                            'd-m-Y H:i:s'
                                        ) }}

                                    @else

                                        <span class="text-muted">
                                            Sin información
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center text-muted py-4"
                                >
                                    No existen valores UF almacenados.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if ($ufs->hasPages())

            <div class="card-footer clearfix">

                {{ $ufs->links() }}

            </div>

        @endif

    </div>

@stop


@section('css')
    <style>

        .table td,
        .table th {
            vertical-align: middle;
        }

    </style>
@stop


@section('js')

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const formulario = document.querySelector(
                'form[action="{{ route('admin.syquac.uf.sincronizar') }}"]'
            );

            if (!formulario) {
                return;
            }

            formulario.addEventListener('submit', function () {

                const boton = formulario.querySelector(
                    'button[type="submit"]'
                );

                boton.disabled = true;

                boton.innerHTML =
                    '<i class="fas fa-spinner fa-spin mr-1"></i>' +
                    ' Sincronizando...';

            });

        });

    </script>

@stop
