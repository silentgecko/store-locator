<?php

namespace Mablae\StoreLocator\StoreList;


use Doctrine\Common\Collections\ArrayCollection;
use Mablae\StoreLocator\Model\PointInterface;

class InMemoryStoreListProvider implements StoreListProvider
{

    private $stores = [
    ];

    public function __construct()
    {

        $this->stores = [
            [
                "Sottrum",
                "53.116032",
                "9.226753",
            ],
            [
                "Cuxhaven",
                "53.859336",
                "8.6879057",
            ],
            [
                "Bremen",
                "53.050305",
                "8.7827539",
            ],

        ];
    }

    public function findAll()
    {
        $storeList = new ArrayCollection();
        foreach ($this->stores as $item) {

            $store = new class() implements PointInterface
            {
                private $title;
                private $latitude;
                private $longitude;

                /**
                 * @return mixed
                 */
                public function getTitle()
                {
                    return $this->title;
                }

                /**
                 * @param mixed $title
                 */
                public function setTitle($title)
                {
                    $this->title = $title;
                }

                /**
                 * @return mixed
                 */
                public function getLatitude(): float
                {
                    return $this->latitude;
                }

                /**
                 * @param mixed $latitude
                 */
                public function setLatitude($latitude)
                {
                    $this->latitude = $latitude;
                }

                /**
                 * @return mixed
                 */
                public function getLongitude(): float
                {
                    return $this->longitude;
                }

                /**
                 * @param mixed $longitude
                 */
                public function setLongitude($longitude)
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
