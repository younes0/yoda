<!DOCTYPE HTML>
<html lang="fr">

<head>
    <title>@yield('title', 'Yoda')</title>

    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="@yield('description', 'Yoda description')">

    <link rel="shortcut icon" href="{{env('ASSETS_URL')}}/images/favicon.ico">
    <link rel="apple-touch-icon" sizes="118x118" href="{{env('ASSETS_URL')}}/images/logo-sm.png" />

    <!-- stylesheets -->
    {!! HTML::style('//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&lang=fr') !!}
    {!! HTML::style('//cdn.jsdelivr.net/fontawesome/4.3.0/css/font-awesome.min.css') !!}
    
    <link rel="stylesheet" href="{!! env('STATIC_URL') . elixir('css/vendor.css') !!}">
    <link rel="stylesheet" href="{!! env('STATIC_URL') . elixir('css/adminlte.css') !!}">
    <link rel="stylesheet" href="{!! env('STATIC_URL') . elixir('css/app.css') !!}">

    <!-- Google Font -->
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,800,300italic,400italic,600italic">
</head>

<body class="{{$bodyClass or null}} hold-transition skin-green sidebar-mini">

<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="/" class="logo">
            {{ config('app.name') }}
            <span class="inline label label-danger">
                {{ App::environment() }}
            </span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
        </nav>
    </header>

    @include('partials._navbar')

    {{-- Content --}}
    <div class="content-wrapper">

        @section('top')
            {{-- Alert [error, success etc] messages --}}
            @foreach (Alert::getMessages() as $type => $messages)
                <div class="alert alert-{{ $type }} padding-xs-vr">
                    @foreach ($messages as $message)
                        {!! $message !!}
                    @endforeach
                </div>
            @endforeach

            {{-- Laravel error messages --}}
            @if ($errors->any())
                <div class="row wrapper bg-danger alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @show
        
        <!-- Content Header (Page header) -->
        @yield('header')

        <!-- Main content -->
        <section class="content">
            @yield('content')
        </section>
    </div>

</div>

{!! App::make('JSLocalizeDumper')->dump('app') !!}

{{-- js cdn --}}
{!! HTML::script('//cdn.jsdelivr.net/g/jquery@2.1.4,bootstrap@3.3.4,jquery.bootstrapvalidator@0.5.3,slimscroll@1.3.6') !!}

{{-- js local --}}
<script src="{!! env('STATIC_URL') . elixir('js/vendor.js') !!}"></script>
<script src="{!! env('STATIC_URL') . elixir('js/adminlte.js') !!}"></script>
<script src="{!! env('STATIC_URL') . elixir('js/app.js') !!}"></script>
<script src="{!! env('STATIC_URL') . elixir('js/snippets.js') !!}"></script>
<script src="{!! env('STATIC_URL') . elixir('js/lang_fr.js') !!}"></script>

@if (env('APP_DEBUG'))
    @if (App::isLocal())
        {{-- {!! HTML::script('//'.env('DOMAIN').':35721/livereload.js') !!} --}}
    @endif
@endif

</body>
</html>
