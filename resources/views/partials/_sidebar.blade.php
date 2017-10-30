<?php

$items = [];

if (Auth::check()) {
    $items = array_merge($items, [
        [_('Accueil'), '/home', 'home'],
        [_('Links'), '/links', 'link'],
        [_('Utilisateurs'), '/users', 'group'],
        [_('Paramètres'), '/settings', 'cog'],
        [_('Logs'), '/logs', 'file-text-o '],
    ]);

    if (App::environment() !== 'production') {
        $items[] = [_('NLP'), '/nlp/documents', 'tag'];
    }
}

?>

<div id="main-menu" role="navigation">
	<div id="main-menu-inner">
		<ul class="navigation">
        @foreach ($items as $item)
            <li class="{{ Request::is(ltrim($item[1], '/')) ? 'active' : '' }}">
                <a href="{{ $item[1] }}" title="{{ $item[0] }}">
					<i class="menu-icon fa fa-{{ $item[2] }}"></i>
					<span class="mm-text">{{ $item[0] }}</span>
                </a>
            </li>
        @endforeach
		</ul>
	</div>
</div>
