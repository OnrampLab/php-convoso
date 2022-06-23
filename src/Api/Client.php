<?php

namespace OnrampLab\Convoso\Api;

use GuzzleHttp\Client as HttpClient;
use Psr\Http\Message\ResponseInterface;

class Client
{
    public Lead $lead;

    protected string $authToken;
    protected HttpClient $httpClient;

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

    public function setHttpClient(HttpClient $httpClient): void
    {
        $this->httpClient = $httpClient;
    }

    public function getEndPoint(string $action = ''): string
    {
        return "https://api.convoso.com/v1/$action";
    }

    public function request(string $method, string $url, array $payload = null, array $options = []): ResponseInterface
    {
        $options = array_merge([
            'json' => $payload,
        ], $options);
        return $this->httpClient->request($method, $url, $options);
    }
}
