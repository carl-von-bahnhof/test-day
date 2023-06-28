<?php

namespace App\Data;

use App\Data\Exception\InvalidArgumentException;

class ProductData
{
    private const FIELD_ID = 'id';
    private const FIELD_WIDTH = 'width';
    private const FIELD_HEIGHT = 'height';
    private const FIELD_LENGTH = 'length';
    private const FIELD_WEIGHT = 'weight';

    private const MANDATORY_FIELDS = [
        self::FIELD_ID,
        self::FIELD_WEIGHT,
        self::FIELD_HEIGHT,
        self::FIELD_LENGTH,
        self::FIELD_WEIGHT
    ];
    private int $id;
    private float $width;

    private float $height;

    private float $length;

    private float $weight;

    private function __construct() {

    }



    public static function createFromArray(array $data): self
    {
        self::validateInput($data);

        $product = new self();
        $product->setId((int) $data[self::FIELD_ID]);
        $product->setWidth((float) $data[self::FIELD_WIDTH]);
        $product->setHeight((float) $data[self::FIELD_HEIGHT]);
        $product->setLength((float) $data[self::FIELD_LENGTH]);
        $product->setWeight((float) $data[self::FIELD_WEIGHT]);

        return $product;

    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getWidth(): float
    {
        return $this->width;
    }

    public function getHeight(): float
    {
        return $this->height;
    }

    public function getLength(): float
    {
        return $this->length;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setWidth(float $width): void
    {
        $this->width = $width;
    }

    public function setHeight(float $height): void
    {
        $this->height = $height;
    }

    public function setLength(float $length): void
    {
        $this->length = $length;
    }

    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }

    private static function validateInput(array $data): void
    {
        //TODO: can be separate validation injected either here or better in the mapper
        //TODO: validate also type
        foreach (self::MANDATORY_FIELDS as $field)
        if(!isset($data[$field])) {
            throw new InvalidArgumentException("Field {$field} is missing!");
        }


    }

}
