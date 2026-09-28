<div class="form-group">
    <label for="name">Nombre</label>
    <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $application->name ?? '') }}" required maxlength="255">
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="form-group">
    <label for="description">Descripción</label>
    <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $application->description ?? '') }}</textarea>
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="form-group">
    <label for="location">Ubicación</label>
    <input id="location" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $application->location ?? '') }}" maxlength="255">
    @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="form-group">
    <label for="model_ids">Modelos utilizados</label>
    @php($selectedModels = array_map('strval', (array) old('model_ids', isset($application) ? $application->models->pluck('id')->all() : [])))
    <select id="model_ids" name="model_ids[]" class="form-control @error('model_ids') is-invalid @enderror @error('model_ids.*') is-invalid @enderror" multiple size="{{ min(max($models->count(), 3), 10) }}">
        @foreach ($models as $model)
            <option value="{{ $model->id }}" @selected(in_array((string) $model->id, $selectedModels, true))>{{ $model->name }}</option>
        @endforeach
    </select>
    <small class="form-text text-muted">Seleccione los modelos de información que utiliza esta aplicación. Puede dejar la selección vacía.</small>
    @error('model_ids') <div class="invalid-feedback">{{ $message }}</div> @enderror
    @error('model_ids.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<button class="btn btn-primary" type="submit">Guardar</button>
<a class="btn btn-secondary" href="{{ route('admin.applications.index') }}">Cancelar</a>
