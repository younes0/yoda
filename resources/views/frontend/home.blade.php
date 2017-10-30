@extends('layouts.master') 

@section('top')
    @embed('embed._header')
        @section('title', 'Accueil')
    @endembed

    @parent 
@stop

@section('content')

@markdown
@endmarkdown

@stop
