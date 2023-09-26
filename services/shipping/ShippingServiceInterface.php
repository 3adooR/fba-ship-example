<?php

namespace App\Services\shipping;

use App\Services\buyer\BuyerInterface;
use App\Services\order\AbstractOrder;

interface ShippingServiceInterface
{
    /**
     * Ship order
     *
     * @param AbstractOrder $order
     * @param BuyerInterface $buyer
     * @return mixed
     */
    public function ship(AbstractOrder $order, BuyerInterface $buyer): string;
}