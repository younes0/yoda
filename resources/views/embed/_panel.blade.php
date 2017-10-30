<div 
	class="panel panel-@yield('type', 'default')" 
	id="@yield('id', '')" 
>
    <div class="panel-heading">
        <h3 class="panel-title">@yield('title', 'Actions')</h3>
    </div>
    <div class="panel-body">
        @yield('body', 'Default Body')
    </div>
    <div class="panel-footer clearfix">
        @yield('footer', '')
    </div>
</div> 