<x-admin-layout>

    <x-slot name="content_header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="mb-0">Planificación Estratégica Ciclo de Vida</h1>
                <small class="text-muted">
                    Control del PECV del Sistema activo
                </small>
            </div>

            <div class="mt-2 mt-md-0">
                <label class="small text-muted mb-1 d-block">
                    Sistema activo
                </label>

                @if ($activeSystem)
                    <form method="POST"
                          action="{{ route('admin.system.active') }}"
                          class="form-inline">

                        @csrf

                        <label for="system_id" class="mr-2 mb-0">
                            Sistema
                        </label>

                        <select
                            id="system_id"
                            name="system_id"
                            class="form-control"
                            onchange="this.form.submit()"
                        >
                            @foreach ($systems as $system)
                                <option
                                    value="{{ $system->id }}"
                                    @selected($activeSystem->id === $system->id)
                                >
                                    {{ $system->identity->entity->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>


    <div class="container-fluid">

        {{-- =========================================================
             SISTEMA
        ========================================================== --}}

        <div class="mb-3">
            <h4 class="mb-0">
                <i class="fas fa-project-diagram mr-2"></i>
                Sistema
            </h4>

            <small class="text-muted">
                Estado general del Sistema y su PECV
            </small>
        </div>


        <div class="row">

            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>1</h3>
                        <p>Sistema activo</p>
                    </div>

                    <div class="icon">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                </div>
            </div>


            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>1</h3>
                        <p>Usuarios autorizados</p>
                    </div>

                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>


            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>0</h3>
                        <p>Aplicaciones</p>
                    </div>

                    <div class="icon">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                </div>
            </div>


            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>0</h3>
                        <p>Documentos</p>
                    </div>

                    <div class="icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
            </div>

        </div>


        <div class="row">

            <div class="col-lg-8">

                <div class="card card-outline card-primary h-100">

                    <div class="card-header">
                        <h3 class="card-title">
                            Estado del PECV
                        </h3>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4 text-center border-right">
                                <div class="description-block">
                                    <h5 class="description-header">—</h5>
                                    <span class="description-text">
                                        CUMPLIMIENTO
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-4 text-center border-right">
                                <div class="description-block">
                                    <h5 class="description-header">0</h5>
                                    <span class="description-text">
                                        OBJETIVOS
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-4 text-center">
                                <div class="description-block">
                                    <h5 class="description-header">0</h5>
                                    <span class="description-text">
                                        METAS
                                    </span>
                                </div>
                            </div>

                        </div>

                        <hr>

                        <div class="progress-group">
                            Plan estratégico
                            <span class="float-right">
                                <b>0</b>/100
                            </span>

                            <div class="progress progress-sm">
                                <div class="progress-bar bg-primary"
                                     style="width: 0%">
                                </div>
                            </div>
                        </div>

                        <div class="progress-group">
                            Ejecución administrativa
                            <span class="float-right">
                                <b>0</b>/100
                            </span>

                            <div class="progress progress-sm">
                                <div class="progress-bar bg-info"
                                     style="width: 0%">
                                </div>
                            </div>
                        </div>

                        <div class="progress-group">
                            Ejecución financiera
                            <span class="float-right">
                                <b>0</b>/100
                            </span>

                            <div class="progress progress-sm">
                                <div class="progress-bar bg-success"
                                     style="width: 0%">
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>


            <div class="col-lg-4">

                <div class="card card-outline card-secondary h-100">

                    <div class="card-header">
                        <h3 class="card-title">
                            Control del Sistema
                        </h3>
                    </div>

                    <div class="card-body p-0">

                        <table class="table table-sm mb-0">

                            <tbody>

                                <tr>
                                    <td>Estado PECV</td>
                                    <td class="text-right">
                                        <span class="badge badge-secondary">
                                            Sin medición
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Administrativo</td>
                                    <td class="text-right">—</td>
                                </tr>

                                <tr>
                                    <td>Financiero</td>
                                    <td class="text-right">—</td>
                                </tr>

                                <tr>
                                    <td>Actores</td>
                                    <td class="text-right">0</td>
                                </tr>

                                <tr>
                                    <td>Modelos gestionados</td>
                                    <td class="text-right">0</td>
                                </tr>

                                <tr>
                                    <td>Interacciones</td>
                                    <td class="text-right">0</td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>



        {{-- =========================================================
             MEWO
        ========================================================== --}}

        <div class="mt-5 mb-3">

            <h4 class="mb-0">
                <i class="fas fa-cubes mr-2"></i>
                MeWo
            </h4>

            <small class="text-muted">
                Producción de actividades por contexto
            </small>

        </div>


        <div class="row">

            @php
                $contextos = [
                    ['Reflexividad', 'fas fa-brain'],
                    ['Administración', 'fas fa-user-tie'],
                    ['Salud', 'fas fa-heartbeat'],
                    ['Comercial', 'fas fa-store'],
                    ['Finanzas', 'fas fa-cash-register'],
                    ['Aprovisionamiento', 'fas fa-warehouse'],
                    ['Mantenimiento', 'fas fa-tools'],
                    ['Capacitación', 'fas fa-chalkboard-teacher'],
                    ['Desarrollo', 'fas fa-code'],
                    ['Jurídico', 'fas fa-balance-scale'],
                    ['Comunicación', 'fas fa-broadcast-tower'],
                ];
            @endphp


            @foreach ($contextos as [$contexto, $icono])

                <div class="col-xl-3 col-lg-4 col-md-6">

                    <div class="info-box">

                        <span class="info-box-icon bg-light">
                            <i class="{{ $icono }}"></i>
                        </span>

                        <div class="info-box-content">

                            <span class="info-box-text">
                                {{ $contexto }}
                            </span>

                            <span class="info-box-number">
                                0
                                <small>actividades</small>
                            </span>

                            <div class="progress">
                                <div class="progress-bar"
                                     style="width: 0%">
                                </div>
                            </div>

                            <span class="progress-description">
                                Sin producción registrada
                            </span>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>


        <div class="row">

            <div class="col-lg-8">

                <div class="card">

                    <div class="card-header border-0">
                        <h3 class="card-title">
                            Producción por contexto
                        </h3>
                    </div>

                    <div class="card-body">

                        <div class="dashboard-chart-placeholder">

                            <div>
                                <i class="fas fa-chart-bar fa-3x mb-3"></i>

                                <p class="mb-0">
                                    Grafo de producción MeWo
                                </p>

                                <small>
                                    Actividades por contexto
                                </small>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-lg-4">

                <div class="card">

                    <div class="card-header border-0">
                        <h3 class="card-title">
                            Indicadores MeWo
                        </h3>
                    </div>

                    <div class="card-body p-0">

                        <table class="table table-sm">

                            <tbody>

                                <tr>
                                    <td>Actividades</td>
                                    <td class="text-right">
                                        <strong>0</strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Interacciones</td>
                                    <td class="text-right">
                                        <strong>0</strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Modelos</td>
                                    <td class="text-right">
                                        <strong>0</strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Entidades</td>
                                    <td class="text-right">
                                        <strong>0</strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Tiempo registrado</td>
                                    <td class="text-right">
                                        <strong>0 h</strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Tiempo planificado</td>
                                    <td class="text-right">
                                        <strong>0 h</strong>
                                    </td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>



        {{-- =========================================================
             SYQUAC
        ========================================================== --}}

        <div class="mt-5 mb-3">

            <h4 class="mb-0">
                <i class="fas fa-chart-line mr-2"></i>
                SyQuAc
            </h4>

            <small class="text-muted">
                Cuantificación del trabajo
            </small>

        </div>


        <div class="row">

            <div class="col-lg-3 col-md-6">

                <div class="info-box">

                    <span class="info-box-icon bg-info">
                        <i class="far fa-clock"></i>
                    </span>

                    <div class="info-box-content">
                        <span class="info-box-text">
                            Tiempo registrado
                        </span>

                        <span class="info-box-number">
                            0 h
                        </span>
                    </div>

                </div>

            </div>


            <div class="col-lg-3 col-md-6">

                <div class="info-box">

                    <span class="info-box-icon bg-warning">
                        <i class="fas fa-calendar-alt"></i>
                    </span>

                    <div class="info-box-content">
                        <span class="info-box-text">
                            Tiempo planificado
                        </span>

                        <span class="info-box-number">
                            0 h
                        </span>
                    </div>

                </div>

            </div>


            <div class="col-lg-3 col-md-6">

                <div class="info-box">

                    <span class="info-box-icon bg-success">
                        <i class="fas fa-balance-scale"></i>
                    </span>

                    <div class="info-box-content">
                        <span class="info-box-text">
                            Unidades
                        </span>

                        <span class="info-box-number">
                            0
                        </span>
                    </div>

                </div>

            </div>


            <div class="col-lg-3 col-md-6">

                <div class="info-box">

                    <span class="info-box-icon bg-danger">
                        <i class="fas fa-dollar-sign"></i>
                    </span>

                    <div class="info-box-content">
                        <span class="info-box-text">
                            Valorización
                        </span>

                        <span class="info-box-number">
                            0 UF
                        </span>
                    </div>

                </div>

            </div>

        </div>


        <div class="row">

            <div class="col-lg-6">

                <div class="card">

                    <div class="card-header border-0">
                        <h3 class="card-title">
                            Tiempo planificado / registrado
                        </h3>
                    </div>

                    <div class="card-body">

                        <div class="dashboard-chart-placeholder">

                            <div>
                                <i class="fas fa-chart-area fa-3x mb-3"></i>

                                <p class="mb-0">
                                    Grafo temporal
                                </p>

                                <small>
                                    Planificación frente a ejecución
                                </small>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-lg-6">

                <div class="card">

                    <div class="card-header border-0">
                        <h3 class="card-title">
                            Producción SyQuAc
                        </h3>
                    </div>

                    <div class="card-body">

                        <div class="dashboard-chart-placeholder">

                            <div>
                                <i class="fas fa-chart-pie fa-3x mb-3"></i>

                                <p class="mb-0">
                                    Distribución de unidades
                                </p>

                                <small>
                                    UnNE · UnSe · UnSo · UnOp · UnEl
                                </small>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="card card-outline card-warning mt-3 mb-5">

            <div class="card-header">
                <h3 class="card-title">
                    Alertas de gestión
                </h3>
            </div>

            <div class="card-body">

                <div class="text-muted text-center py-3">
                    <i class="fas fa-check-circle mr-1"></i>
                    No existen alertas conectadas al Dashboard.
                </div>

            </div>

        </div>

    </div>


    <x-slot name="css">

        <style>

            .info-box {
                min-height: 100px;
            }

            .info-box-text {
                white-space: normal;
            }

            .dashboard-chart-placeholder {
                height: 280px;
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
                border: 1px dashed #ced4da;
                border-radius: 4px;
                color: #6c757d;
                background: #f8f9fa;
            }

            .description-block {
                margin: 10px 0;
            }

        </style>

    </x-slot>


    <x-slot name="js">
    </x-slot>

</x-admin-layout>
