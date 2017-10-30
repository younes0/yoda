<div id="main-navbar" class="navbar navbar-inverse">

	<!-- Main menu toggle -->
	<button type="button" id="main-menu-toggle">
		<i class="navbar-icon fa fa-bars icon"></i>
		<span class="hide-menu-text">HIDE MENU</span>
	</button>
	
	<div class="navbar-inner">
		<div class="navbar-header">
			<a href="/home" class="navbar-brand">
				<strong>{{ config('app.name') }}</strong>
				@if (App::environment() === 'production')
					<span class="label label-danger">Prod</span>
				@endif
			</a>

			<!-- Main navbar toggle -->
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar-collapse">
				<i class="navbar-icon fa fa-bars"></i>
			</button>
		</div>
	</div>

	<div id="main-navbar-collapse" class="collapse navbar-collapse main-navbar-collapse">
		<div><div class="right clearfix">
			<ul class="nav navbar-nav pull-right right-navbar-nav">
				@if (Auth::check())
					<li class="dropdown">
						<a href="#" class="dropdown-toggle user-menu" data-toggle="dropdown">
							<span>{{ Auth::user()->getFullname() }}</span>
						</a>
						<ul class="dropdown-menu">
							<li>
			                    <a href="/auth/logout">
			                        <i class="fa fa-sign-out"></i>
			                        Log out
			                    </a>
                			</li>
						</ul>
					</li>
				@else
               <li>
                    <a href="/auth/login">
                        <i class="fa fa-sign-in"></i>
                        Log in
                    </a>
                </li>
				@endif
			</ul>
		</div></div>
	</div>
</div>
