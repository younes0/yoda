@extends('layouts.master') 

@section('top')
    @embed('embed._header')
        @section('icon', 'group')
        @section('title', _('Utilisateurs'))
        @section('right')
            <a href="/users" class="btn btn-primary">
                Créer un utilisateur
            </a>
        @endsection
    @endembed

    @parent 
@stop

@section('content')

@embed('embed._datatable')
    @section('title', _('Utilisateurs'))
@endembed

@stop
