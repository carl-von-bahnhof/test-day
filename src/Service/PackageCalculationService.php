<?php

namespace App\Service;

use App\Data\ProductCollection;
use App\Entity\Packaging;

class PackageCalculationService
{

    /**
     * @param ProductCollection $productCollection
     * @param Packaging[] $packages
     * @return Packaging|null
     */
    public function calculate(ProductCollection $productCollection, array $packages): ?Packaging
    {
        $fittingPackage = null;
        $neededTotalSpace = 0;
        $maxWeight = 0;


        foreach ($productCollection->getItems() as $productData) {
            $neededTotalSpace += $productData->getLength() * $productData->getHeight() * $productData->getWidth();
            $maxWeight += $productData->getWeight();
        }

        foreach ($packages as $packaging) {

            if($neededTotalSpace < $packaging->calculateDimensions() && $maxWeight < $packaging->getMaxWeight()) {
                if($fittingPackage === null || $packaging->calculateDimensions() < $fittingPackage->calculateDimensions()) {
                    $fittingPackage = $packaging;
                }
            }
        }

        return $fittingPackage;
    }

}
