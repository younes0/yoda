@extends('layouts.modal')

<?php

$props = [
    'is_ignored'       => _('Ignorer ses liens'),
    'is_banned'        => _('Ignorer ses liens et ceux qui l\'ont tweeté'), 
    'is_trusted'       => _('approuver tous les liens'),
    'can_have_paywall' => _('Peut avoir un Paywall'),
];

?>

@section('title')
    Host
@stop

@section('body')
{!! Former::open()->id('modalForm')->action(URL::current()) !!} 

    {!! Former::text('url') !!}
    
    @foreach ($props as $field => $label)
        {!! Former::checkbox($field)->text($label) !!}
    @endforeach

{!! Former::close() !!}
@stop

@section('footer')
    <div class="modalButtons">
        <button class="btn btn-primary" type="submit" form="modalForm">
            Create Host
        </button>
    </div>
@stop
