<?php

namespace Mablae\StoreLocator;

use Doctrine\Common\Collections\ArrayCollection;
use Geocoder\Exception\NoResult;
use Geocoder\Model\Address;
use Geocoder\Model\AddressCollection;
use Geocoder\Model\Coordinates;
use Geocoder\ProviderAggregator;
use Mablae\StoreLocator\DistanceCalculator\DistanceCalculatorInterface;
use Mablae\StoreLocator\Model\LocatedStore;
use Mablae\StoreLocator\Model\LocatedStoreList;
use Mablae\StoreLocator\Model\Point;
use Mablae\StoreLocator\StoreList\StoreListProvider;

final class StoreLocator
{

    /**
     * @var ArrayCollection
     */
    private $storeList;

    /**
     * @var ProviderAggregator
     */
    private $geocoder;
    /**
     * @var DistanceCalculatorInterface
     */
    private $distanceCalculator;
    /**
     * @var StoreListProvider
     */
    private $storeListProvider;

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

        try {
            $results = $this->geocoder->using('free_geo_ip')->geocode($ipAddress);
        } catch (NoResult $e) {
            throw $e;
        }

        if ($results->count() === 0) {
            throw new NoResult();
        }

        return $this->calculateDistances($results);


    }
    public function locateBySearchTerm(string $searchTerm): LocatedStoreList
    {
        $this->initStores();
        try {
            $results = $this->geocoder->using('google_maps')->geocode($searchTerm);
        } catch (NoResult $e) {
            throw $e;
        }


        if ($results->count() === 0) {
            throw new NoResult();
        }

        return $this->calculateDistances($results);

    }
    public function locateByCoordinate(Coordinates $coordinates): LocatedStoreList
    {
        $this->initStores();

        return new LocatedStoreList(
            $this->locate($coordinates), new Address(
                $coordinates,
                null,
                null,
                null,
                null,
                'Koordinaten: '.$coordinates->getLatitude().'/'.$coordinates->getLongitude()
            )
        );

    }
    private function initStores()
    {
        if (null === $this->storeList) {
            $this->storeList = $this->storeListProvider->findAll();
        }
    }

    /**
     * @param $results
     * @return LocatedStoreList
     */
    private function calculateDistances(AddressCollection $results): LocatedStoreList
    {
        $address = $results->first();

        $results = $this->locate($address->getCoordinates());

        return new LocatedStoreList($results, $address);
    }

    /**
     * @param Coordinates $coordinates
     * @return ArrayCollection
     */
    private function locate(Coordinates $coordinates): ArrayCollection
    {
        $point = new Point($coordinates->getLatitude(), $coordinates->getLongitude());

        $results = new ArrayCollection();

        foreach ($this->storeList as $storePage) {
            $distance = $this->distanceCalculator->calculateDistance($point, $storePage);

            $locatedStore = new LocatedStore($distance, $storePage);
            $results->add($locatedStore);
        }


        return $this->sortResultsByDistance($results);

    }

    /**
     * @param $results ArrayCollection
     * @return ArrayCollection
     */
    private function sortResultsByDistance(ArrayCollection $results): ArrayCollection
    {
        $iterator = $results->getIterator();
        $iterator->uasort(
            function ($a, $b) {

                /**
                 * @var $a LocatedStore
                 * @var $b LocatedStore
                 */
                return ($a->getDistanceToPoint() < $b->getDistanceToPoint()) ? -1 : 1;
            }
        );

        return new ArrayCollection(iterator_to_array($iterator));
    }




}
