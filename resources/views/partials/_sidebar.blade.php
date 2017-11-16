<?php

$links = [
    '/'         => trans('app.sidebar_home'),
    '/links'    => 'Links',
    '/users'    => 'Users',
    1           => 'divider',
    '/settings' => 'Settings',
    '/logs'     => 'Logs',
];

?>

@if (Auth::check())
    <aside class="main-sidebar">
        <section class="sidebar">
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">

                <li class="header">App</li>
                
                @foreach ($links as $path => $label)
                    @if ($label === 'divider')
                        <li class="header divider"></li>
                    @else
                        <li>
                            <a href="{{ url($path) }}">
                                <span>{{ $label }}</span>
                            </a>
                        </li>
                    @endif
                @endforeach

                @if (Auth::user()->is_admin)
                    <li class="header">{{ trans('app.administration') }}</li>
                    <li>
                        <a href="{{ url('/admin/users') }}">
                            <span>{{ trans('app.users') }}</span>
                        </a>
                    </li>
                @endif
            </ul>
        </section>
    </aside>
@endif
