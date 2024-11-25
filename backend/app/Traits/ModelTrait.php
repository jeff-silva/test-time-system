<?php

namespace App\Traits;

use Illuminate\Support\Fluent;
use Illuminate\Support\Str;
use Illuminate\Http\Request;


trait ModelTrait
{
    public function scopeSearch($query, $params = [], $returnData = false)
    {
        $params = new Request(array_merge([
            'page' => 1,
            'per_page' => 20,
            'order_by' => 'id',
            'order' => 'desc',
        ], $params));

        $query->orderBy($params->order_by, $params->order);

        $query = $this->searchQuery($query, $params);
        $data = $query->paginate($params->per_page)->toArray();

        if ($returnData) {
            return new Fluent([
                'pagination' => [
                    'page' => $data['current_page'],
                    'pages' => $data['last_page'],
                    'per_page' => $data['per_page'],
                    'total' => $data['total'],
                ],
                'data' => $data['data'],
                // 'sql' => $this->scopeToRawSql($query),
            ]);
        }

        return $query;
    }

    public function searchParams()
    {
        return $this->searchParamsDefaults([]);
    }

    public function searchParamsDefaults($merge = [])
    {
        return array_merge([
            'search' => null,
            'page' => 1,
            'per_page' => 20,
            'order_by' => 'id',
            'order' => 'desc',
        ], $merge);
    }

    protected function searchQuery($query, $params)
    {
        return $query;
    }

    public static function scopeToRawSql($query)
    {
        return Str::replaceArray('?', collect($query->getBindings())
            ->map(function ($i) {
                if (is_object($i)) {
                    $i = (string) $i;
                }
                return (is_string($i)) ? "'$i'" : $i;
            })->all(), $query->toSql());
    }
}
