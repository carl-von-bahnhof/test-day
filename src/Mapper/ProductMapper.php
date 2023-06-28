<?php

namespace App\Mapper;

use App\Data\ProductCollection;
use App\Data\ProductData;
use App\Mapper\Exception\MapperException;
use Psr\Http\Message\RequestInterface;

class ProductMapper
{
    public function mapProductRequest(RequestInterface $request): ProductCollection
    {

        $jsonData = json_decode($request->getBody()->getContents(), true);

        if (!$jsonData) {
            throw new MapperException('Unable to decode request.'); // could be dedicated exception
        }


        if (array_keys($jsonData,'products')) {
            throw new MapperException('Unable to find products.'); // could be dedicated exception
        }
        $collection = new ProductCollection();
        array_map(function (array $productItem) use ($collection) {
            $collection->addItem(ProductData::createFromArray($productItem));

        }, $jsonData['products']);

        return $collection;

    }

}
