<x-admin-layout>
    <x-slot name="content_header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div><h1 class="mb-0">Bitácora</h1><small>{{ \Carbon\CarbonImmutable::parse($date)->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY') }}</small></div>
            <div class="d-flex align-items-center flex-wrap" style="gap:8px">
                @if($activeSystem)
                    <form method="POST" action="{{ route('admin.system.active') }}">@csrf
                        <label class="sr-only" for="daily-system">Sistema</label>
                        <select id="daily-system" name="system_id" class="form-control" onchange="this.form.submit()">
                            @foreach($systems as $system)<option value="{{ $system->id }}" @selected($activeSystem->id===$system->id)>{{ $system->identity->entity->name }}</option>@endforeach
                        </select>
                    </form>
                @endif
                <form method="GET" action="{{ route('admin.posts.index') }}">
                    <label class="sr-only" for="daily-date">Fecha</label>
                    <input id="daily-date" type="date" name="date" value="{{ $date }}" class="form-control" onchange="this.form.submit()">
                </form>
                <a class="btn btn-link text-muted" href="{{ route('admin.binnacles.index') }}">Histórico</a>
            </div>
        </div>
    </x-slot>

    @include('admin.mewo._messages')
    @if(!$binnacle)<p>No hay un Sistema disponible.</p>@else
    <div class="card card-outline card-secondary"><div class="card-body py-3">
        <div class="row">
            @foreach(['existential'=>'Existencial','leisure'=>'Ocio','business'=>'Negocio','rest'=>'Descanso'] as $key=>$label)
                @php($seconds=$indicators['seconds'][$key])
                <div class="col-6 col-lg-3 mb-2">
                    <div class="text-muted">{{ $label }}</div>
                    <strong>{{ intdiv($seconds,3600) }} h {{ intdiv($seconds%3600,60) }} min</strong>
                    <span class="text-muted ml-2">{{ $indicators['percentages'][$key] }} %</span>
                    <div class="progress mt-2" style="height:4px"><div class="progress-bar bg-secondary" role="progressbar" style="width:{{ $indicators['percentages'][$key] }}%" aria-valuenow="{{ $indicators['percentages'][$key] }}" aria-valuemin="0" aria-valuemax="100" aria-label="{{ $label }}"></div></div>
                </div>
            @endforeach
        </div>
        @php($unrecorded=max(0,$indicators['day_seconds']-$indicators['recorded_seconds']))
        <small class="text-muted">Tiempo sin registrar: {{ intdiv($unrecorded,3600) }} h {{ intdiv($unrecorded%3600,60) }} min. Porcentajes de la jornada, según intervalos registrados.</small>
    </div></div>

    <div class="card"><div class="card-header"><h3 class="card-title">Proposiciones</h3></div><div class="card-body">
        <form method="POST" action="{{ route('propositions.store') }}" class="mb-3">@csrf
            <input type="hidden" name="binnacle_id" value="{{ $binnacle->id }}">
            <label class="sr-only" for="daily-sentence">Agregar proposición</label>
            <textarea id="daily-sentence" name="sentence" class="form-control" rows="2" required placeholder="Escribe tu proposición…">{{ old('sentence') }}</textarea>
            <button class="btn btn-primary btn-sm mt-2">Agregar proposición</button>
        </form>
        @forelse($propositions as $proposition)
            <div class="border-top py-2"><small class="text-muted mr-2">{{ $proposition->created_at->timezone('America/Santiago')->format('H:i') }}</small><span style="white-space:pre-wrap">{{ $proposition->sentence }}</span></div>
        @empty<p class="text-muted mb-0">Sin proposiciones registradas para esta jornada.</p>@endforelse
    </div></div>

    <div class="card"><div class="card-header"><h3 class="card-title">Actividades programadas</h3></div><div class="card-body py-2">
        @forelse($programmed as $activity)<div class="py-2"><a href="{{ route('admin.activities.show',$activity) }}">{{ $activity->name }}</a></div>@empty<p class="text-muted my-2">Sin actividades programadas para esta jornada.</p>@endforelse
    </div></div>
    <div class="card"><div class="card-header"><h3 class="card-title">Actividades no programadas</h3></div><div class="card-body py-2">
        @forelse($unprogrammed as $activity)<div class="d-flex justify-content-between align-items-center py-2"><a href="{{ route('admin.activities.show',$activity) }}">{{ $activity->name }}</a>
            <form method="POST" action="{{ route('admin.binnacle.program',$activity) }}">@csrf<input type="hidden" name="date" value="{{ $date }}"><button class="btn btn-outline-secondary btn-sm">Programar</button></form>
        </div>@empty<p class="text-muted my-2">Sin actividades pendientes de programación.</p>@endforelse
    </div></div>

    <details class="mb-3"><summary class="text-muted">Registrar tiempo</summary>
        <div class="card mt-2"><div class="card-body"><form method="POST" action="{{ route('admin.binnacle.time') }}">@csrf
            <input type="hidden" name="system_id" value="{{ $activeSystem->id }}"><input type="hidden" name="date" value="{{ $date }}">
            <div class="form-group"><label for="period-interaction">Interacción entre actores</label><select id="period-interaction" name="interaction_id" class="form-control"><option value="">Seleccione la interacción</option>
                @foreach($interactions as $interaction)<option value="{{ $interaction->id }}">{{ $interaction->actorA->identity->entity->name }} ↔ {{ $interaction->actorB->identity->entity->name }} · {{ $interaction->cohesion->name }}</option>@endforeach
            </select></div>
            <label><input id="period-rest" name="rest" type="checkbox" value="1"> Registrar descanso</label>
            <div class="row"><div class="col-md-6 form-group"><label for="period-start">Inicio</label><input id="period-start" name="starts_at" type="datetime-local" class="form-control" value="{{ old('starts_at') }}" required></div>
            <div class="col-md-6 form-group"><label for="period-end">Término</label><input id="period-end" name="ends_at" type="datetime-local" class="form-control" value="{{ old('ends_at') }}" required></div></div>
            <button class="btn btn-outline-secondary">Guardar tiempo</button>
        </form></div></div>
    </details>
    <details class="mb-3"><summary class="text-muted">Modelos de esta jornada</summary><div class="mt-2">
        @forelse($binnacle->models as $model)<p>{{ $model->name }}</p>@empty<p class="text-muted">Sin modelos asociados.</p>@endforelse
        <a href="{{ route('admin.binnacle-models.create',['binnacle_id'=>$binnacle->id]) }}">Agregar modelo</a>
    </div></details>
    @endif
    <x-slot name="js"><script>
        const rest=document.getElementById('period-rest');
        if(rest)rest.addEventListener('change',()=>{document.getElementById('period-interaction').disabled=rest.checked;});
    </script></x-slot>
</x-admin-layout>
