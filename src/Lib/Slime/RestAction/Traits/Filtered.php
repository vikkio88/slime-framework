<?php

namespace App\Lib\Slime\RestAction\Traits;

use App\Lib\Slime\Models\SlimeModel;

trait Filtered
{

    public function getFromRequest(SlimeModel $model, array $params)
    {
        $query = $model->select();
        $acceptedFilters = $model->getAcceptedFilters();

        $childrenIds = [];

        foreach ($acceptedFilters as $filterName) {
            if (isset($params[$filterName])) {

                if (isset($configurations[$filterName]['type'])
                    &&
                    $configurations[$filterName]['type'] == 'children'
                ) {

                    $childrenIds[] = $this->getIdsFromChildren(
                        [
                            $configurations[$filterName]['table'],
                            $configurations[$filterName]['parent_id'],
                            $configurations[$filterName]['col'],
                            $configurations[$filterName]['op'],
                            $params[$filterName]
                        ]
                    );
                } else {
                    $query->where(
                        $configurations[$filterName]['col'],
                        $configurations[$filterName]['op'],
                        $params[$filterName]
                    );
                }

            }
        }
        foreach ($childrenIds as $ids) {
            $query->whereIn('id', $ids);
        }

        return $query;
    }

    public function getIdsFromChildren(array $params = [])
    {
        return $params;
    }
}