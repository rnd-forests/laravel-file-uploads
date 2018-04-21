<?php

return [

    'audio' => [
        'max_size' => 50 * 1024,
        'mimes' => [
            'audio/mpeg',
            'audio/x-wav',
            'audio/mp4',
            'audio/x-ms-wma',
            'audio/ogg'
        ],
    ],

    'avatar' => [
        'max_size' => 2 * 1024,
        'mimes' => [
            'image/jpeg',
            'image/png',
        ],
    ],

];
