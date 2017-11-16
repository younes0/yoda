<div class="navbar-custom-menu pull-left">
    <ul class="nav navbar-nav">
        {{-- <li><a href="{{ url('/') }}"><i class="fa fa-home"></i> <span>Home</span></a></li> --}}
    </ul>
</div>

<div class="navbar-custom-menu">
    <ul class="nav navbar-nav">

        @if (Auth::guest())
            <li>
                <a href="{{ url('/login') }}">
                    {{ trans('app.authent_login') }}
                </a>
            </li>
        @else
            <li>
                <a href="{{ url('/logout') }}">
                    <i class="fa fa-btn fa-sign-out"></i>
                    {{ trans('app.authent_logout') }}
                </a>
            </li>
        @endif

    </ul>
</div>
