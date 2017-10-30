@extends('layouts.master') 

@section('top')
    @parent 
@stop

@section('content')
    <form method="POST" action="/auth/register" name="register" id="register">
        {!! csrf_field() !!}

        <div class="col-md-6">
            First Name
            <input type="text" name="firstname" value="{{ old('firstname') }}">
        </div>
        <div class="col-md-6">
            Last Name
            <input type="text" name="lastname" value="{{ old('lastname') }}">
        </div>
        <div>
            Email
            <input type="email" name="email" value="{{ old('email') }}">
        </div>

        <div>
            Password
            <input type="password" name="password">
        </div>

        <div class="col-md-6">
            Confirm Password
            <input type="password" name="password_confirmation">
        </div>

        <div>
            <button type="submit">Register</button>
        </div>
    </form>
@stop
