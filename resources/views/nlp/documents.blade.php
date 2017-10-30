@extends('layouts.master') 

@section('top')
    @embed('embed._header')
        @section('icon', 'table')
        @section('title', 'Documents - remaining: '.Session::get('nlpDocsRemaining'))

        @section('right')
            @include('nlp._nav')
        @endsection
    @endembed
@stop

@section('content')

<div class="panel panel-default">

@if (isset($doc))
    <div class="panel-body clearfix">
        {!! Former::open()->action(URL::current().'/action')->class('form-inline') !!} 

            <i class="fa fa-tags"></i>
            {{ $doc->class }}   

            {!! Former::select('class')
                ->class('select2')
                ->options(array_combine($classes, $classes), $doc->classified_as)
                ->label(null) !!}

            <input value="Reclassify" name="action[reclassify]" type="submit" class="btn btn-success" />
            <input value="Pass" name="action[pass]" type="submit" class="btn btn-primary" />
            <input value="Delete" name="action[delete]" type="submit" class="btn btn-danger pull-right" />

        {!! Former::close() !!}
    </div>    

    <div class="panel-footer nlpHighlight">
        {!! nl2br($doc->content) !!}
    </div>
@else
    <div class="panel-body text-center">
        <h4>No content</h4>
    </div>
@endif

</div>

@stop
