<?php 
$lang = substr(Request::server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
?>

<!DOCTYPE html>
<html>
<head>
	<title>
        @if ($lang === 'fr') 
            Site temporairement indisponible
        @else
            Site is down for maintenance 
        @endif
	</title>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<style>
    	body { text-align: center; }
    	h1 { font-size: 40px; }
    	body { font: 20px Helvetica, sans-serif; color: #333;}
    	.container {
			width        : auto; 
			max-width    : 650px;
			margin-right : auto;
			margin-left  : auto;
			padding-left : 15px;
			padding-right: 15px;
    	}
    	article { display: block; text-align: left; width: 100%; margin: 0 auto; }
    	a { color: #dc8100; text-decoration: none; }
    	a:hover { color: #333; text-decoration: none; }
    </style>
</head>

<body>

<div class="container">
<article>
	@if ($lang === 'fr')
		<h1>{{ Config::get('app.name') }} est temporairement indisponible.</h1>
		<p>Nous effectuons des opérations de maintenance. Le site sera disponible dans quelques instants</p>
		<p>Veuillez nous excuser pour ce désagrément.</p>
		<p>Tenez-vous au courant sur <a href="http://www.twitter.com/{{Config::get('yskel.twitter')}}">Twitter</a></p>
	@else
		<h1>{{ Config::get('app.name') }} is temporary unavailable.</h1>
		<p>We are currently performing scheduled maintenance. Site will back soon.</p>
		<p>We apologize for any inconvenience.</p>
		<p>Get updates on <a href="http://www.twitter.com/{{Config::get('yskel.twitter')}}">Twitter</a></p>
	@endif
</article>
</div>

</body>
</html>
