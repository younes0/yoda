@extends('layouts.master') 

@section('top')
    @embed('embed._header')
        @section('icon', 'table')
        @section('title', 'Train - remaining: '.Session::get('nlpTrainRemaining'))

        @section('right')
            @include('nlp._nav')
        @endsection
    @endembed

    @parent
@stop

@section('content')

<div class="panel panel-default">
 
@if (isset($model))
    <div class="panel-heading">
        {!! HTML::link($model->url) !!}
    </div>

    <div class="panel-body clearfix row">
        <div class="col-sm-8">
            {!! Former::open()->action(URL::current().'/action')->class('form-inline') !!} 

                @if ($model->nlpClassed)
                    <i class="fa fa-tags"></i>
                    {!! $model->nlpClassed->class !!} 
                @endif

                {!! Former::select('class')
                    ->class('select2')
                    ->options($classes, $model->classified_as ?: '{"domain":"nonlaw-fr","class":"false"}')
                    ->label(null) !!}

                <input value="Create Document" name="action[create]" type="submit" class="btn btn-success" />
                <input value="Pass" name="action[pass]" type="submit" class="btn btn-primary" />

            {!! Former::close() !!}
        </div>    

        <div class="col-sm-4 text-right">
            @include('partials._host', ['url' => $model->url])
        </div>
    </div>    

    <div class="panel-footer nlpHighlight">
        {!! nl2br($model->getNlpProps()['content']) !!}
    </div>
@else
    <div class="panel-body text-center">
        <h4>No content</h4>
    </div>
@endif
    
</div>

@stop
