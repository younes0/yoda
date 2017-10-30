<?php

return [

    // filesystem disk: local, s3, rackspace
    'default' => 'local',

    // cloud disk
    'cloud' => 's3',

    // filesystem "disks"
    'disks' => [

        'local' => [
            'driver' => 'local',
            'root'   => storage_path().'/app',
        ],

        's3' => [
            'driver' => 's3',
            'key'    => env('AWS_KEY'),
            'secret' => env('AWS_SECRET'),
            'region' => env('AWS_REGION', 'eu-west-1'),
            'bucket' => env('AWS_S3_BUCKET'),
        ],

        // spatie media library
        'media' => [
            'driver' => 'local',
            'root'   => public_path().'/media',
        ],

    ],

];
