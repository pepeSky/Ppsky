@php($naming = app(\App\Services\MeWo\ApplicationNaming::class))
@php($inputName = old('name', $application->name ?? ''))
<p><strong>Sistema:</strong> {{ $activeSystem->identity->display_name }}</p>
@if (isset($application) && !$application->system_id)<p class="text-muted">Aplicación anterior sin sistema asignado. Al guardar quedará vinculada al sistema actual.</p>@endif
<div class="form-group">
    <label for="name">Nombre de la aplicación</label>
    <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $inputName }}" required maxlength="255">
    <small class="form-text text-muted">Nombre en el sistema: <strong id="application-name-preview" aria-live="polite">{{ $naming->name($inputName) ?: '—' }}</strong></small>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="form-group">
    <label for="description">Descripción</label>
    <textarea id="description" name="description" readonly data-system-name="{{ $activeSystem->identity->display_name }}" class="form-control @error('description') is-invalid @enderror" rows="3">{{ $naming->description($inputName, $activeSystem->identity->display_name) }}</textarea>
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="form-group">
    <label for="location">Ubicación</label>
    <input id="location" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $application->location ?? '') }}" maxlength="255">
    @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<fieldset class="form-group">
    <legend class="h6">Plataformas compatibles</legend>
    @php($selectedPlatforms = (array) old('compatible_platforms', $application->compatible_platforms ?? []))
    @foreach (config('application_platforms') as $key => $label)
        <div class="form-check form-check-inline">
            <input type="checkbox" class="form-check-input" id="platform-{{ $key }}" name="compatible_platforms[]" value="{{ $key }}" @checked(in_array($key, $selectedPlatforms, true))>
            <label class="form-check-label" for="platform-{{ $key }}">{{ $label }}</label>
        </div>
    @endforeach
    @error('compatible_platforms') <div class="text-danger">{{ $message }}</div> @enderror
    @error('compatible_platforms.*') <div class="text-danger">{{ $message }}</div> @enderror
</fieldset>
<button class="btn btn-primary" type="submit">Guardar</button>
<a class="btn btn-secondary" href="{{ route('admin.applications.index') }}">Cancelar</a>

<script src="{{ asset('pepesky/application-naming.js') }}" defer></script>
