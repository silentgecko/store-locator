<?php


namespace Mablae\StoreLocator\Model;


use Doctrine\Common\Collections\ArrayCollection;
use Geocoder\Model\Address;

final class LocatedStoreList
{
    private Address $originAddress;
    private ArrayCollection $storeList;

    public function __construct(ArrayCollection $storeList, Address $originAddress)
    {
        $this->storeList = $storeList;

        $this->originAddress = $originAddress;
    }

    public function getOriginAddress(): Address
    {
        return $this->originAddress;
    }

    public function getStoreList(): ArrayCollection
    {
        return $this->storeList;
    }


}
