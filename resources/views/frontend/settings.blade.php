@extends('layouts.master') 

@section('top')
    <div class="page-header">
        <h1 class="col-sm-6 col-xs-12">
            <i class="menu-icon fa fa-cog"></i>
            {{_('Paramètres')}}
        </h1>
    </div>

    @parent 
@stop

@section('content')

@embed('embed._panel')
    @section('title', 'Edit')
    @section('body')
        <a class="btn btn-default" href="/oauth/twitter">
            Twitter token
        </a>
        
        <a class="btn btn-default" href="/oauth/jediwp">
            Jediwp token
        </a>
    @endsection
@endembed

@stop
