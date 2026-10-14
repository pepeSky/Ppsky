<dl class="row">
<dt class="col-sm-4">Tipo de persona</dt><dd class="col-sm-8">{{ ['natural'=>'Natural','legal'=>'Jurídica','other'=>'Otro elemento'][$identity->person_type] ?? '—' }}</dd>
<dt class="col-sm-4">País del identificador (CL, etc.)</dt><dd class="col-sm-8">{{ $identity->identifier_country ?? '—' }}</dd>
<dt class="col-sm-4">Tipo de identificador (RUT, etc.)</dt><dd class="col-sm-8">{{ $identity->identifier_kind ?? '—' }}</dd>
<dt class="col-sm-4">Número de identificación</dt><dd class="col-sm-8">{{ $identity->identifier_number ?? '—' }}</dd>
<dt class="col-sm-4">Primer nombre</dt><dd class="col-sm-8">{{ $identity->first_name ?? '—' }}</dd>
<dt class="col-sm-4">Segundo nombre</dt><dd class="col-sm-8">{{ $identity->second_name ?? '—' }}</dd>
<dt class="col-sm-4">Tercer nombre</dt><dd class="col-sm-8">{{ $identity->third_name ?? '—' }}</dd>
<dt class="col-sm-4">Apellido paterno</dt><dd class="col-sm-8">{{ $identity->paternal_surname ?? '—' }}</dd>
<dt class="col-sm-4">Apellido materno</dt><dd class="col-sm-8">{{ $identity->maternal_surname ?? '—' }}</dd>
<dt class="col-sm-4">Razón social</dt><dd class="col-sm-8">{{ $identity->legal_name ?? '—' }}</dd>
<dt class="col-sm-4">Nacionalidad</dt><dd class="col-sm-8">{{ $identity->nationality ?? '—' }}</dd>
<dt class="col-sm-4">Fecha de nacimiento</dt><dd class="col-sm-8">{{ $identity->birth_date ?? '—' }}</dd>
<dt class="col-sm-4">Correo electrónico</dt><dd class="col-sm-8">{{ $identity->email ?? '—' }}</dd>
<dt class="col-sm-4">Teléfono</dt><dd class="col-sm-8">{{ $identity->phone ?? '—' }}</dd>
<dt class="col-sm-4">Dirección</dt><dd class="col-sm-8">{{ $identity->address ?? '—' }}</dd>
<dt class="col-sm-4">Ciudad / comuna</dt><dd class="col-sm-8">{{ $identity->city ?? '—' }}</dd>
<dt class="col-sm-4">Región</dt><dd class="col-sm-8">{{ $identity->region ?? '—' }}</dd>
<dt class="col-sm-4">País</dt><dd class="col-sm-8">{{ $identity->country ?? '—' }}</dd>
</dl>
@if(collect($identity->personal_information ?? [])->contains(fn($value)=>filled($value)))
<h3 class="h5">Información personal</h3>
@foreach(config('identity_information.personal') as $key=>$label)
@if(filled(($identity->personal_information ?? [])[$key] ?? null))<h4 class="h6">{{ $label }}</h4><p style="white-space:pre-wrap">{{ $identity->personal_information[$key] }}</p>@endif
@endforeach
@endif
