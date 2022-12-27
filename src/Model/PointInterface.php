<?php

namespace Silentgecko\StoreLocator\Model;

interface PointInterface
{
    public function getLatitude(): float;

    public function getLongitude(): float;
}
