<?php

namespace Silentgecko\StoreLocator\DistanceCalculator;

use Silentgecko\StoreLocator\Model\PointInterface;

interface DistanceCalculatorInterface
{
    public function calculateDistance(PointInterface $first, PointInterface $second): float;
}
