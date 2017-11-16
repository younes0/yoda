<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title>{{ config('app.name') }}</title>

    @yield('before_styles')

    {{-- CDN --}}
    <link rel="stylesheet" href="//cdn.jsdelivr.net/bootstrap/3.3.7/css/bootstrap.min.css"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/fontawesome/4.7.0/css/font-awesome.min.css"></script>

    <!-- CDN DataTable -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/1.3.1/css/buttons.bootstrap.min.css"/>
    <link rel="stylesheet" href="//cdn.datatables.net/fixedcolumns/3.2.2/css/fixedColumns.bootstrap.min.css"/>
    <link rel="stylesheet" href="//cdn.datatables.net/fixedheader/3.1.2/css/fixedHeader.bootstrap.min.css"/>
    <link rel="stylesheet" href="//cdn.datatables.net/colreorder/1.4.1/css/colReorder.dataTables.min.css"/>
    <link rel="stylesheet" href="//cdn.datatables.net/keytable/2.3.2/css/keyTable.dataTables.min.css"/>

    {{-- App CSS --}}
    <link rel="stylesheet" href="{{ mix('/css/vendor.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/adminlte.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/yeb.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/yoda.css') }}">

    <!-- Google Font -->
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,800,300italic,400italic,600italic">
</head>

<body class="hold-transition skin-green sidebar-mini">

{{-- Wrapper --}}
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
            @include('partials/_menu')
        </nav>
    </header>

    {{-- Sidebar --}}
    @include('partials/_sidebar')

    {{-- Content --}}
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        @yield('header')

        <!-- Main content -->
        <section class="content">
            @yield('content')
        </section>
    </div>
</div>

<!-- CDN -->
<script src="//cdn.jsdelivr.net/g/jquery@3.2.1,bootstrap@3.3.7,lodash@4.17.4"></script>

<!-- CDN DataTable -->
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>

<script src="//cdn.datatables.net/buttons/1.3.1/js/buttons.bootstrap.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.3.1/js/buttons.colVis.min.js"></script>

<script src="//cdn.datatables.net/fixedcolumns/3.2.2/js/dataTables.fixedColumns.min.js"></script>
<script src="//cdn.datatables.net/fixedheader/3.1.2/js/dataTables.fixedHeader.min.js"></script>
<script src="//cdn.datatables.net/colreorder/1.4.1/js/dataTables.colReorder.min.js"></script>
<script src="//cdn.datatables.net/scroller/1.4.3/js/dataTables.scroller.min.js"></script>
<script src="//cdn.datatables.net/keytable/2.3.2/js/dataTables.keyTable.min.js"></script>

{!! App::make('JSLocalizeDumper')->dump('app') !!}

<!-- App scripts -->
<script src="{!! mix('/js/vendor.js') !!}"></script>
<script src="{!! mix('/js/adminlte.js') !!}"></script>
<script src="{!! mix('/js/Routes.js') !!}"></script>

</body>
</html>
