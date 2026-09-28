<x-admin-layout>
    <x-slot name="content_header"><h1>Modificar aplicación</h1></x-slot>
    <div class="card"><div class="card-body">
        <form action="{{ route('admin.applications.update', $application) }}" method="POST">
            @csrf @method('PUT')
            @include('admin.applications._form')
        </form>
    </div></div>
</x-admin-layout>
