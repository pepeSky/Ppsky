<x-admin-layout>
    <x-slot name="content_header"><h1>Modificar aplicación: {{ $application->name }}</h1></x-slot>
    <x-slot name="css">
        @if (!config('adminlte.livewire')) @livewireStyles @endif
        @include('admin.applications.modal-styles')
    </x-slot>
    @if (session('info')) <div class="alert alert-success">{{ session('info') }}</div> @endif
    <div class="card"><div class="card-body">
        <form action="{{ route('admin.applications.update', $application) }}" method="POST">
            @csrf @method('PUT')
            @include('admin.applications._form')
        </form>
    </div></div>
    <livewire:admin.application-models :application-id="$application->id" />
    <x-slot name="js">@if (!config('adminlte.livewire')) @livewireScripts @endif</x-slot>
</x-admin-layout>
