<x-admin-layout>
    <x-slot name="content_header"><h1>Agregar aplicación</h1></x-slot>
    <div class="card"><div class="card-body">
        <form action="{{ route('admin.applications.store') }}" method="POST">
            @csrf
            @include('admin.applications._form')
        </form>
    </div></div>
</x-admin-layout>
