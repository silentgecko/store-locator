<?php

namespace Silentgecko\StoreLocator\StoreList;

use Doctrine\Common\Collections\ArrayCollection;
use Silentgecko\StoreLocator\Model\PointInterface;

class InMemoryStoreListProvider
{
    private array $stores = [
        [
            "Sottrum",
            53.116032,
            9.226753,
        ],
        [
            "Cuxhaven",
            53.859336,
            8.6879057,
        ],
        [
            "Bremen",
            53.050305,
            8.7827539,
        ],

    ];

    public function findAll(): ArrayCollection
    {
        $storeList = new ArrayCollection;
        foreach ($this->stores as $item) {
            $store = new class() implements PointInterface {
                private string $title;
                private float $latitude;
                private float $longitude;

                public function getTitle(): string
                {
                    return $this->title;
                }

                public function setTitle(string $title)
                {
                    $this->title = $title;
                }

                public function getLatitude(): float
                {
                    return $this->latitude;
                }

                public function setLatitude(float $latitude)
                {
                    $this->latitude = $latitude;
                }

                public function getLongitude(): float
                {
                    return $this->longitude;
                }

                public function setLongitude(float $longitude)
                {
                    $this->longitude = $longitude;
                }

            };

            $store->setTitle($item[0]);
            $store->setLatitude($item[1]);
            $store->setLongitude($item[2]);

            $storeList->add($store);
        }

        return $storeList;
    }
}
