<?php

$actions = [
    ['ignore', 'default', 'Ignore'],
    ['trust', 'primary', 'Trust'],
    ['ban', 'danger', 'Ban'],
    ['paywall', 'default', 'Paywall']
];

?>

@foreach ($actions as $action)
    <a 
        href="/host/{{ $action[0] }}?url={{ $url }}" 
        class="btn btn-sm btn-{{ $action[1] }}"
        title="{{ $action[2] }} host"
    >
        {{$action[2]}}
    </a>
@endforeach
