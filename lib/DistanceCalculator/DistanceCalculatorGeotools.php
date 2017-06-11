<?php

namespace Mablae\StoreLocator\DistanceCalculator;

use League\Geotools\Coordinate\Coordinate;
use League\Geotools\Geotools;
use Mablae\StoreLocator\Model\PointInterface;

class DistanceCalculatorGeotools implements DistanceCalculatorInterface
{

    private $geotools;

    public function __construct()
    {
        $this->geotools = new Geotools();
    }

    public function calculateDistance(PointInterface $first, PointInterface $second): float
    {

        $coordA   = new Coordinate([$first->getLatitude(), $first->getLongitude()]);
        $coordB   = new Coordinate([$second->getLatitude(), $second->getLongitude()]);

        $distance = $this->geotools->distance()->setFrom($coordA)->setTo($coordB);

        return $distance->haversine();

    }
}
