<?php

return [
    'driver'     => env('MAIL_DRIVER', 'smtp'),
    'host'       => env('MAIL_HOST', 'mailtrap.io'),
    'port'       => env('MAIL_PORT', 2525),
    'from'       => ['address' => 'younes.elbiache@gmail.com', 'name' => null],
    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    'username'   => env('MAIL_USERNAME'),
    'password'   => env('MAIL_PASSWORD'),
    'sendmail'   => '/usr/sbin/sendmail -bs',
    'pretend'    => false,
];
