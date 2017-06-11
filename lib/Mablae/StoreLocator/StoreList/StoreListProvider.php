<?php

namespace Mablae\StoreLocator\StoreList;

use Mablae\StoreLocator\Model\PointInterface;

interface StoreListProvider
{

    /**
     * @return PointInterface[]|array
     */
    public function findAll();

}
