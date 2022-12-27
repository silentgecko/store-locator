<?php
/**
 * Created by PhpStorm.
 * User: mablae
 * Date: 11.06.2017
 * Time: 01:45
 */

namespace Mablae\StoreLocator\Model;


class LocatedStore
{

    private float $distanceToPoint;


    private $locatedItem;


    public function __construct(float $distanceToPoint, $locatedItem)
    {
        $this->distanceToPoint = $distanceToPoint;
        $this->locatedItem = $locatedItem;
    }

    public function getDistanceToPoint(): float
    {
        return $this->distanceToPoint;
    }

    public function getLocatedItem()
    {
        return $this->locatedItem;
    }

}
