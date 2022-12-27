<?php

namespace Mablae\StoreLocator;

use Doctrine\Common\Collections\ArrayCollection;
use Geocoder\Exception\CollectionIsEmpty;
use Geocoder\Model\Address;
use Geocoder\Model\AddressCollection;
use Geocoder\Model\AdminLevelCollection;
use Geocoder\Model\Coordinates;
use Geocoder\ProviderAggregator;
use Mablae\StoreLocator\DistanceCalculator\DistanceCalculatorInterface;
use Mablae\StoreLocator\Model\LocatedStore;
use Mablae\StoreLocator\Model\LocatedStoreList;
use Mablae\StoreLocator\Model\Point;
use Mablae\StoreLocator\StoreList\StoreListProvider;

final class StoreLocator
{
    private ArrayCollection $storeList;
    private ProviderAggregator $geocoder;
    private DistanceCalculatorInterface $distanceCalculator;
    private StoreListProvider $storeListProvider;

    public function __construct(
        StoreListProvider $storeRepository,
        ProviderAggregator $geocoder,
        DistanceCalculatorInterface $distanceCalculator
    ) {
        $this->geocoder = $geocoder;
        $this->distanceCalculator = $distanceCalculator;
        $this->storeListProvider = $storeRepository;
    }

    public function locateByIpAddress(string $ipAddress): LocatedStoreList
    {
        $this->initStores();

        $results = $this->geocoder->using('free_geo_ip')->geocode($ipAddress);

        return $this->calculateDistances($results);
    }

    public function locateBySearchTerm(string $searchTerm): LocatedStoreList
    {
        $this->initStores();
        $results = $this->geocoder->using('google_maps')->geocode($searchTerm);

        return $this->calculateDistances($results);
    }

    public function locateByCoordinate(Coordinates $coordinates): LocatedStoreList
    {
        $this->initStores();

        return new LocatedStoreList(
            $this->locate($coordinates),
            new Address(
                self::class,
                new AdminLevelCollection,
                $coordinates,
                null,
                null,
                null,
                null,
                'Koordinaten: ' . $coordinates->getLatitude() . '/' . $coordinates->getLongitude()
            )
        );
    }

    private function initStores(): void
    {
        if (null === $this->storeList) {
            $this->storeList = $this->storeListProvider->findAll();
        }
    }

    private function calculateDistances(AddressCollection $results): LocatedStoreList
    {
        if (empty($results)) {
            throw new CollectionIsEmpty();
        }

        $address = $results->first();

        $results = $this->locate($address->getCoordinates());

        return new LocatedStoreList($results, $address);
    }

    private function locate(Coordinates $coordinates): ArrayCollection
    {
        $point = new Point($coordinates->getLatitude(), $coordinates->getLongitude());

        $results = new ArrayCollection;

        foreach ($this->storeList as $storePage) {
            $distance = $this->distanceCalculator->calculateDistance($point, $storePage);

            $locatedStore = new LocatedStore($distance, $storePage);
            $results->add($locatedStore);
        }

        return $this->sortResultsByDistance($results);
    }

    private function sortResultsByDistance(ArrayCollection $results): ArrayCollection
    {
        $iterator = $results->getIterator();
        $iterator->uasort(
            fn($a, $b) => /**
                 * @var $a LocatedStore
                 * @var $b LocatedStore
                 */
            ($a->getDistanceToPoint() < $b->getDistanceToPoint()) ? -1 : 1
        );

        return new ArrayCollection(iterator_to_array($iterator));
    }

}
