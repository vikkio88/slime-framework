<?php

namespace App\Lib\Slime\Models;

use App\Lib\Slime\Models\Helper\Constants as c;
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
            'op' => c::EQUAL
        ]
    ];

    public static function scopePage($query, $pagination)
    {
        $limit = $pagination['limit'];
        $offset = ($pagination['page'] - 1) * $pagination['limit'];
        return $query->take($limit)->skip($offset);
    }

    public function getAcceptedFilters()
    {

    }

}