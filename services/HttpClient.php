<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class HttpClient
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Sending request by HTTP
     *
     * @param array $request
     * @return array
     */
    public function sendRequest(array $request): array
    {
        try {
            $response = $this->client->request(
                $request['method'],
                $request['url'],
                [
                    'headers' => $request['headers'],
                    'body' => $request['body'],
                ]
            );

            return [
                'status' => $response->getStatusCode(),
                'body' => $response->getBody()->getContents(),
            ];
        } catch (GuzzleException|\Exception $e) {
            return [
                'status' => $e->getCode(),
                'body' => $e->getMessage(),
            ];
        }
    }
}