<x-admin-layout>
    <x-slot name="content_header">
        <a class="btn btn-secondary float-right" href="{{ route('admin.applications.edit', $application) }}">Volver a la aplicación</a>
        <h1>Diccionario — {{ $application->name }}</h1>
        <p>Sistema: {{ $application->system?->identity?->display_name ?? 'Sin sistema asignado' }}</p>
    </x-slot>
    <x-slot name="css">@if (!config('adminlte.livewire')) @livewireStyles @endif@include('admin.applications.modal-styles')<style>
        .dictionary-list { max-height: 65vh; overflow-y: auto; }
        .dictionary-text { white-space: pre-wrap; overflow-wrap: anywhere; }
        .dictionary-entry { min-height: 20rem; }
    </style></x-slot>
    <div class="d-flex flex-wrap align-items-start">
        <livewire:admin.application-models :application-id="$application->id" :compact="true" />
        <livewire:admin.dictionary-transfer :application-id="$application->id" />
    </div>
    <livewire:admin.application-dictionary :application-id="$application->id" :initial-search="$initialSearch" :initial-term="$initialTerm" />
    <x-slot name="js">@if (!config('adminlte.livewire')) @livewireScripts @endif</x-slot>
</x-admin-layout>
