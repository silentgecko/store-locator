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

    /**
     * @var float
     */
    private $distanceToPoint;


    private $locatedItem;


    public function __construct(float $distanceToPoint, $locatedItem)
    {
        $this->distanceToPoint = $distanceToPoint;
        $this->locatedItem = $locatedItem;
    }

    /**
     * @return float
     */
    public function getDistanceToPoint(): float
    {
        return $this->distanceToPoint;
    }

    /**
     * @return mixed
     */
    public function getLocatedItem()
    {
        return $this->locatedItem;
    }

}
