<?php

namespace App\Mapper;

use App\Data\ProductCollection;
use App\Data\ProductData;

class CollectionToHashMapper
{
    public static function getHashFromCollection(ProductCollection $productCollection): string
    {
        $ids = [];
        array_map(function (ProductData $productData) use (&$ids) {
            $ids[] = $productData->getId();
        },$productCollection->getItems());

        sort($ids);

        return md5(implode('|', $ids));
    }

}
