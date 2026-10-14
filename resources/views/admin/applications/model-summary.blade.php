@php($modelLanguages = $application->models->flatMap(fn ($model) => $model->definitions->pluck('language'))->filter()->unique('id')->sortBy('name'))
<div class="model-count">{{ $application->models->count() }} {{ $application->models->count() === 1 ? 'modelo' : 'modelos' }}</div>
<div class="text-muted">Lenguajes: {{ $modelLanguages->pluck('name')->implode(', ') ?: 'Sin lenguajes referenciados' }}</div>
<a class="model-dictionary-link" href="{{ route('admin.applications.dictionary', $application) }}">Ver diccionario</a>
