@extends('adminlte::page')

@section('title', 'pepeSky - Administration')

@section('content_header')
    @if (isset($content_header))
        {{ $content_header }}
    @endif
@stop

@section('content')
    {{ $slot }}
@stop

@section('css')
    @if (isset($css))
        {{ $css }}
    @endif
@stop

@section('js')
    @if (isset($js))
        {{ $js }}
    @endif
@stop
