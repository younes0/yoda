@extends('layouts.master') 

@section('top')
    @embed('embed._header')
        @section('icon', 'table')
        @section('title', $title)
    @endembed

    @parent 
@stop

@section('content')

@embed('embed._datatable')
    @section('title', $title)
@endembed

@stop
