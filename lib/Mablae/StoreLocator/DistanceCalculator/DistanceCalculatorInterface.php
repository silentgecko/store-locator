<?php

namespace Mablae\StoreLocator\DistanceCalculator;

use Mablae\StoreLocator\Model\PointInterface;

interface DistanceCalculatorInterface
{
    public function calculateDistance(PointInterface $first, PointInterface $second): float;
}
