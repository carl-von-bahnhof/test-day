<?php

namespace App\Http;

use App\Data\ProductCollection;
use App\Data\ProductData;
use App\Entity\Packaging;

class PackingRequest
{
    /**
     * @param ProductCollection $productCollection
     * @param Packaging[] $packaging
     */
    public function __construct(private ProductCollection $productCollection, private array $packaging)
    {
    }

    public function getMappedArray(): array
    {

        $products = [];
        $bins = [];

        /**
         * @var ProductData $product
         */


        foreach ($this->productCollection->getItems() as $product) {
            $products[] = [
                'id' => $product->getId(),
                'w' => $product->getWidth(),
                'h' => $product->getHeight(),
                'd' => $product->getLength(),
                'wg' => $product->getWeight(),
                'q' => 1
            ];
        }

        foreach ($this->packaging as $packaging) {

            $bins[] = [
                'id' => $packaging->getId(),
                'w' => $packaging->getWidth(),
                'h' => $packaging->getHeight(),
                'd' => $packaging->getLength(),
                'wg' => $packaging->getMaxWeight(),
                'q' => null,
            ];
        }

        return [
            'items' => $products,
            'bins' => $bins,
        ];


//        {
//            "id":"Too big item",
//            "w":5,
//            "h":5,
//            "d":5,
//            "wg":1,
//            "q":1,
//            "vr":true
//        }

    }

}
