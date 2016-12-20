<?php

namespace App\Lib\Slime\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class SlimeModel extends Eloquent
{

    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot'
    ];

    protected $filters = [
        'id' => [
            'col' => 'id',
            'op' => '='
        ]
    ];

    public static function scopePage($query, $pagination)
    {
        $limit = $pagination['limit'];
        $offset = ($pagination['page'] - 1) * $pagination['limit'];
        return $query->take($limit)->skip($offset);
    }

    public static function scopeFilter($query, array $params)
    {
        $instance = new static;
        $acceptedFilters = $instance->getAcceptedFilters();
        foreach ($acceptedFilters as $filterName => $configuration) {
            if (isset($params[$filterName])) {
                $query->where(
                    $configuration['col'],
                    $configuration['op'],
                    $params[$filterName]
                );
            }
        }
        return $query;
    }

    public function getAcceptedFilters()
    {
        return $this->filters;
    }

}