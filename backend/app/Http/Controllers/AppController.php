<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Controllers\Middleware;

class AppController extends Controller
{
    public function openapi()
    {
        $openapi = [
            'openapi' => '3.0.3',
            'info' => [
                'version' => '1.0.0',
                'title' => env('APP_NAME'),
                'contact' => [
                    'email' => env('MAIL_FROM_ADDRESS'),
                ],
            ],
            'servers' => [
                ['url' => URL::to('/api')],
            ],
            'tags' => [],
            'paths' => [],
            'components' => [
                'securitySchemes' => [
                    'bearerAuth' => [
                        'type' => 'http',
                        'scheme' => 'bearer',
                        'bearerFormat' => 'JWT',
                    ],
                ],
                'schemas' => [
                    // 'Pet' => [
                    //     'type' => 'object',
                    //     'required' => ['petType'],
                    //     'properties' => [
                    //         'petType' => ['type' => 'string'],
                    //     ],
                    // ],
                ],
            ],
        ];

        foreach (Route::getRoutes() as $route) {
            if (!str_starts_with($route->uri, 'api/')) continue;

            if (isset($route->action['controller'])) {
                $controller = preg_replace('/Controller.+/', '', Arr::last(explode('\\', $route->action['controller'])));
                $openapi['tags'][$controller] = [
                    'name' => $controller,
                ];

                $uri = '/' . str_replace('api/', '', $route->uri);

                if (!isset($openapi['paths'][$uri])) {
                    $openapi['paths'][$uri] = [];
                }

                foreach ($route->methods as $method) {
                    if (in_array($method, ['HEAD', 'PATCH'])) continue;
                    $method = strtolower($method);
                    $item = [
                        'tags' => [$controller],
                        'operationId' => $route->getName(),
                        'parameters' => config("openapi.paths.{$uri}.parameters", []),
                        'requestBody' => config("openapi.paths.{$uri}.requestBody", []),
                        'consumes' => ['application/json', 'multipart/form-data'],
                        'produces' => ['application/json'],
                        'responses' => [
                            '200' => ['description' => 'Success'],
                        ],
                    ];

                    if (in_array('api', $route->action['middleware'])) {
                        $item['security'] = [
                            ['bearerAuth' => []],
                        ];
                    }

                    $openapi['paths'][$uri][$method] = $item;
                }
            }
        }

        $openapi['tags'] = array_values($openapi['tags']);

        $models = glob(app_path('/Models/*.php'));
        foreach ($models as $model) {
            $model = str_replace(app_path(), '\App', $model);
            $model = str_replace('.php', '', $model);
            $model = str_replace('/', '\\', $model);
            $model = app($model);

            $table_name = $model->getTable();
            $table_columns = DB::select("show columns from {$table_name} ");

            $schema = [
                'type' => 'object',
                'properties' => ['id' => ['type' => 'bigInteger']],
            ];
            foreach ($table_columns as $col) {
                $type = preg_split('/[^a-zA-Z]/', $col->Type);
                $type = $type[0] ?? 'varchar';
                $schema['properties'][$col->Field] = ['type' => $type];
            }

            $openapi['components']['schemas'][$model->getTable()] = $schema;
        }

        return $openapi;
    }
}
