<?php

namespace App\Lib\Slime\RestAction\Traits;

use App\Lib\Helpers\Config;
use App\Lib\Slime\Models\SlimeModel;

trait Pagination
{
    public function filter(SlimeModel $request)
    {
        $page = $request->getParam(
            $this->pageParam,
            1
        );

        $limit = $request->getParam(
            $this->limitParam,
            Config::get('pagination.defaultLimit')
        );

        $page = $page > 0 ? $page : 1;
        $limit = $limit >= Config::get('pagination.minLimit') && $limit <= Config::get('pagination.maxLimit')
            ? $limit : Config::get('pagination.defaultLimit');

        return [
            'page' => $page,
            'limit' => $limit
        ];
    }

}