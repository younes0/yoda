@extends('layouts.master') 

@section('top')
    @parent 
@stop

@section('content')

  <div class="col-lg-12">

        <div class="ibox">
            <div class="ibox-title float-e-margins">
                <h5>Se connecter</h5>
            </div>

            <div class="ibox-content">
                {!! Form::open([
                    'url'   => '/auth/login',
                    'class' => 'form-horizontal bsValidator',
                ]) !!}

                     <div class="form-group">
                        {!! Form::label('email', 'Adresse email', [
                            'class' => 'control-label col-sm-2'
                        ]) !!}
                        <div class="col-sm-10">
                            {!! Form::input('email', 'email', env('APP_DEBUG') ? 'admin@admin.com' : old('email'), [
                                'class' => 'form-control',
                                'data-bv-notempty' => true,
                            ]) !!}
                        </div>
                    </div>
                                    
                    <div class="form-group">
                        {!! Form::label('password', 'Mot de passe', [
                            'class' => 'control-label col-sm-2'
                        ]) !!}
                        <div class="col-sm-10">
                            {!! Form::input('password', 'password', old('password'), [
                                'class' => 'form-control',
                                'data-bv-notempty' => true,
                            ]) !!}
                        </div>
                    </div>
                                    
                    <div class="form-group">
                        <label for="remember" class="control-label col-sm-2"></label>
                        <div class="col-sm-10">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('remember', old('remember'), true); !!}
                                    Se souvenir de moi
                                </label>
                            </div>
                        </div>
                    </div>
                
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            <button type="submit" class="btn btn-primary">
                                Se connecter
                            </button>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    
@stop

