@extends('layouts.master') 

@section('top')
    @embed('embed._header')
        @section('icon', 'table')
        @section('title', $title)
        @section('right')
            <a href="/links/host-modal" data-toggle="ajax-modal" class="btn btn-default">
                Create Host
            </a>
        @endsection
    @endembed

    @parent 
@stop

@section('content')

@embed('embed._datatable')
    @section('title', $title)
@endembed

@stop
