<?php

namespace App\Services\buyer;

use App\Services\JsonParser;

class Buyer implements BuyerInterface
{
    private $data = [];

    public function __construct(private readonly int $id)
    {
    }

    public function getBuyerId(): int
    {
        return $this->id;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->data[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->data[$offset]);
    }

    public function load(): void
    {
        $this->data = $this->loadBuyerData($this->getBuyerId());
    }

    /**
     * Load order data from mock file
     *
     * @param int $id
     * @return array
     */
    public function loadBuyerData(int $id): array
    {
        return JsonParser::parse('../mock/buyer.'.$id.'.json');
    }
}