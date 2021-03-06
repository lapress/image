<?php

return [
    'default_modification_method' => 'fit',
    'encoded_image' => [
        'quality' => 75,
        'format' => 'jpg', // http://image.intervention.io/api/encode
    ],

    'cache' => [
        'ttl' => 525600
    ],
    'image_manager' => [
        // 'driver' => ''
    ],
    'keep_aspect_ration_on_resize' => true,
    'route' => 'img/{width}/{height}/{method}/{path}',
    'allowed_modification_methods'=> ['fit', 'resize'],
    'storage' => 'local'
];
