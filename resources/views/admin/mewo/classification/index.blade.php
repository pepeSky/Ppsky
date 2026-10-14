<x-admin-layout><x-slot name="content_header"><h1>Orientación de proposiciones</h1></x-slot>
@include('admin.mewo._messages')
<div class="card"><div class="card-body"><form method="POST" action="{{ route('admin.mewo.classification.analyze') }}">@csrf
<label for="sentence">Proposición</label><textarea id="sentence" name="sentence" class="form-control mb-3" required maxlength="5000">{{ old('sentence',$sentence) }}</textarea><button class="btn btn-primary">Proponer contextos y procesos</button></form>
<p class="mt-3">Las coincidencias son sugerencias para revisión. Un verbo puede corresponder a varios contextos; seleccione los procesos al registrar la actividad.</p>
@if($sentence)<table class="table"><thead><tr><th>Verbo</th><th>Fragmento posterior al verbo</th><th>Contexto candidato</th><th>Proceso candidato</th></tr></thead><tbody>@forelse($suggestions as $suggestion)<tr><td>{{ $suggestion['verb'] }} @if($suggestion['negated'])<strong>(posible negación)</strong>@endif</td><td>{{ $suggestion['complement_fragment'] }}</td><td>{{ $suggestion['context'] }}</td><td>{{ $suggestion['process'] }}</td></tr>@empty<tr><td colspan="4">No se encontraron verbos del catálogo. Revise la expresión y los procesos disponibles.</td></tr>@endforelse</tbody></table>@endif
</div></div></x-admin-layout>
