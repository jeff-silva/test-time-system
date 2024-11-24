<?php

return [
    'paths' => [
        '/auth/login' => [
            'requestBody' => [
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'email' => ['type' => 'string', 'default' => 'main@grr.la'],
                                'password' => ['type' => 'string', 'default' => 'main@grr.la'],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
