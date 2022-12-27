<?php

namespace Silentgecko\StoreLocator\StoreList;

use Doctrine\Common\Collections\ArrayCollection;
use Silentgecko\StoreLocator\Model\PointInterface;

interface StoreListProvider
{
    /**
     * @return ArrayCollection<PointInterface>
     */
    public function findAll(): ArrayCollection;

}
