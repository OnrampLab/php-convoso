<?php

namespace OnrampLab\Convoso\Api;

use GuzzleHttp\Client as HttpClient;

class Client
{
    public $lead;

    protected $authToken;
    protected $httpClient;

    public static function create(string $authToken, HttpClient $httpClient = null): Client
    {
        // Find a default HTTP client if none provided
        if (null === $httpClient) {
            $httpClient = new HttpClient();
        }

        $client = new Client();
        $client->authToken = $authToken;
        $client->httpClient = $httpClient;
        $client->lead = new Lead($client);

        return $client;
    }

    public function setHttpClient(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getEndPoint($action = ''): string
    {
        return "https://api.convoso.com/v1/$action";
    }

    public function request(string $method, string $url, array $payload = null, array $options = null)
    {
        return $this->httpClient->request($method, $url, $payload, $options);
    }
}
