<?php

namespace App\Services\order;

use App\Services\JsonParser;

class Order extends AbstractOrder
{
    /**
     * Load order data from mock file
     *
     * @param int $id
     * @return array
     */
    public function loadOrderData(int $id): array
    {
        return JsonParser::parse('../mock/order.'.$id.'.json');
    }
}