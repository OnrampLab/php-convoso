<?php

namespace OnrampLab\Convoso\Api;

use Exception;
use Psr\Http\Message\ResponseInterface;

class Lead
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function add(array $leadInfo): array
    {
        $payload = [
            'query' => [
                // API auth
                'auth_token' => $leadInfo['auth_token'],
                // API metadata
                'list_id' => $leadInfo['list_id'],
                'status' => 'CALLBK',
                // API details
                'phone_code' => 1,
                'province' => $leadInfo['province'],
                'first_name' => $leadInfo['first_name'],
                'last_name' => $leadInfo['last_name'],
                'phone_number' => $leadInfo['phone_number'],
                'email' => $leadInfo['email'],
                'postal_code' => $leadInfo['postal_code'],
                'city' => $leadInfo['city'],
                'state' => $leadInfo['state'],
                'address1' => $leadInfo['address1'],
                'notes' => $leadInfo['comments'], // NOTE: to make API consistent, we use same leadInfo data as e-dialer for now
            ]
        ];

        $url = $this->client->getEndPoint('leads/insert');

        $response = $this->client->request('GET', $url, $payload);

        $result = $this->parseResultFromResponse($response);

        return $result;
    }

    public function search(array $query): array
    {
        $url = $this->client->getEndPoint('leads/search');

        $payload = [
            'query' => $query,
        ];

        $response = $this->client->request('GET', $url, $payload);

        $result = $this->parseResultFromResponse($response);

        return $result;
    }

    public function update(array $leadInfo): array
    {
        $url = $this->client->getEndPoint('leads/update');

        $payload = [
            'form_params' => $leadInfo,
        ];

        $response = $this->client->request('POST', $url, $payload);

        $result = $this->parseResultFromResponse($response);

        return $result;
    }

    protected function parseResultFromResponse(ResponseInterface $response): array
    {
        $body = json_decode($response->getBody(), true);

        if (!$body['success']) {
            throw new Exception($body['text']);
        }

        return $body['data'];
    }
}
