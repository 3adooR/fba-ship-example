<?php

use App\Services\buyer\Buyer;
use App\Services\HttpClient;
use App\Services\order\Order;
use App\Services\shipping\AmazonShippingService;

// Load order
$orderId = $_ENV['ORDER_ID'] ?? 0;
$order = new Order($orderId);
$order->load();
if (empty($order->data) || empty($order->data['client_id'])) {
    throw new Exception('Error on loading order');
}

// Load buyer
$buyer = new Buyer((int) $order->data['client_id']);
$buyer->load();

// Creating Amazon shipping service with HTTP-client
$amazonShippingService = new AmazonShippingService(
    new HttpClient()
);

// Sending order to Amazon FBA
try {
    $trackingNumber = $amazonShippingService->ship($order, $buyer);
    echo 'Tracking number is: '.$trackingNumber;

} catch (Exception $e) {
    echo sprintf('Error on sending data to Amazon: %s', $e->getMessage());
}

