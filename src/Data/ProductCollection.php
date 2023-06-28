<?php

namespace App\Data;


class ProductCollection
{
    private array $items;

    public function addItem(ProductData $productData): void
    {
        $this->items[] = $productData;
    }

    /**
     * @return ProductData[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

}
