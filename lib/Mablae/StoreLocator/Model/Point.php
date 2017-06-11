<?php

namespace Mablae\StoreLocator\Model;


class Point implements PointInterface
{
    /**
     * @var float
     */
    private $latitude;
    /**
     * @var float
     */
    private $longitude;

    public function __construct(float $latitude, float $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

}
