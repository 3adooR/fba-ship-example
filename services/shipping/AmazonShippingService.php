<?php

namespace App\Services\shipping;

use App\Services\buyer\BuyerInterface;
use App\Services\order\AbstractOrder;
use Exception;

class AmazonShippingService implements ShippingServiceInterface
{
    private $apiClient;

    /** @var string|mixed - Amazon api key */
    private string $amazonApiKey;

    /** @var string|mixed - Amazon ship api url */
    private string $amazonUrlShip;

    /**
     * Init http-client and amazon api key
     *
     * @param $apiClient
     */
    public function __construct($apiClient)
    {
        $this->apiClient = $apiClient;
        $this->amazonApiKey = $_ENV['AMAZON_API_KEY'] ?? '';
        $this->amazonUrlShip = $_ENV['AMAZON_URL_SHIP'] ?? '';
    }

    /**
     * Ship order
     *
     * @param AbstractOrder $order
     * @param BuyerInterface $buyer
     * @return mixed
     * @throws Exception
     */
    public function ship(AbstractOrder $order, BuyerInterface $buyer): string
    {
        // Message body
        $messageBody = $this->getMessage($order, $buyer);

        // Creating json request to API Amazon FBA, using order data
        $request = [
            'method' => 'POST',
            'url' => $this->amazonUrlShip,
            'headers' => [
                'Authorization' => 'Bearer '.$this->amazonApiKey,
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($messageBody),
        ];

        // Sending request via http client
        $response = $this->apiClient->sendRequest($request);

        // Getting response and status
        switch ($response['status']) {
            case 403:
                throw new Exception('Access forbidden, check your API KEY in .env');

            case 200:
                $responseData = json_decode($response['body'], true);
                return $responseData['tracking_number'];

            default:
                throw new Exception('Error shipping order to Amazon FBA: '.$response['body']);
        }
    }

    /**
     * Get message to ship
     * @TODO - need to change by current API
     *
     * @param AbstractOrder $order
     * @param BuyerInterface $buyer
     * @return array
     */
    private function getMessage(AbstractOrder $order, BuyerInterface $buyer): array
    {
        return [
            'order_id' => $order->getOrderId() ?? 0,
            'country_id' => $buyer->offsetGet('country_id') ?? '',
            'country_code' => $buyer->offsetGet('country_code') ?? '',
            'country_code3' => $buyer->offsetGet('country_code3') ?? '',
            'shop_username' => $buyer->offsetGet('shop_username') ?? '',
            'email' => $buyer->offsetGet('email') ?? '',
            'phone' => $buyer->offsetGet('phone') ?? '',
            'address' => $buyer->offsetGet('address') ?? '',
        ];
    }
}