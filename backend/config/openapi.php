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
            // 'parameters' => [
            //     [
            //         'name' => 'email',
            //         'in' => 'formData',
            //         'type' => 'string',
            //         'required' => true,
            //         'default' => 'main@grr.la',
            //     ],
            //     [
            //         'name' => 'password',
            //         'in' => 'formData',
            //         'type' => 'string',
            //         'required' => true,
            //         'default' => 'main@grr.la',
            //     ],
            // ],
        ],
    ],
];
