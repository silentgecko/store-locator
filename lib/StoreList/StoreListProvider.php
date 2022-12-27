<?php

namespace Mablae\StoreLocator\StoreList;

use Doctrine\Common\Collections\ArrayCollection;
use Mablae\StoreLocator\Model\PointInterface;

interface StoreListProvider
{

    /**
     * @return ArrayCollection<PointInterface>
     */
    public function findAll(): ArrayCollection;

}
