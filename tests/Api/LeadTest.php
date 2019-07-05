<?php

namespace OnrampLab\Convoso\Api;

use Tests\TestCase;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class LeadTest extends TestCase
{
    protected $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = Client::create('test');

        $this->leadInfo = [
            'auth_token' => 'test_token',
            // API metadata
            'list_id' => '3862',
            // API details
            'lead_id' => '2122933',
            'province' => 'Test',
            'first_name' => 'Anita',
            'last_name' => 'Wang',
            'phone_number' => '1234567890',
            'email' => 'test1234567890@test.com',
            'postal_code' => '90017',
            'city' => 'LA',
            'state' => 'CA',
            'address1' => 'test address',
            'comments' => 'Test Comment',
        ];

        $this->filters = [
            'auth_token' => 'test_token',
            'email' => 'someone@somedoamin.com',
        ];
    }

    public function test_add_success()
    {
        $data = [
            'success' => true,
            'data' => [
                'lead_id' => '65007347',
            ],
        ];
        $httpClient = $this->getHttpClient(200, json_encode($data));

        $this->client->setHttpClient($httpClient);

        $result = $this->client->lead->add($this->leadInfo);

        $this->assertEquals($result['lead_id'], '65007347');
    }

    public function test_add_failure()
    {
        $error = [
            'success' => false,
            'code' => 5001,
            'text' => 'Auth token is invalid',
        ];
        $httpClient = $this->getHttpClient(200, json_encode($error));

        $this->client->setHttpClient($httpClient);

        $this->expectExceptionMessage('Auth token is invalid');

        $this->client->lead->add($this->leadInfo);
    }

    public function test_search_success() {
        $data = [
            'success' => true,
            'data' => [
                'offset' => 0,
                'limit' => 10,
                'total' => 1,
                'entries' => [
                    [
                        'id' => 5,
                        'created_at' => '2015-01-29T14:09:57-0800',
                        'status' => 'CALLBK',
                        'phone_number' => "8181234561",
                    ],
                ],
            ],
        ];
        $httpClient = $this->getHttpClient(200, json_encode($data));

        $this->client->setHttpClient($httpClient);

        $result = $this->client->lead->search($this->filters);

        $this->assertEquals($result['entries'][0]['id'], 5);
    }

    public function test_search_failure() {
        $error = [
            'success' => false,
            'code' => 5001,
            'text' => 'Auth token is invalid',
        ];
        $httpClient = $this->getHttpClient(200, json_encode($error));

        $this->client->setHttpClient($httpClient);

        $this->expectExceptionMessage('Auth token is invalid');

        $this->client->lead->search($this->filters);
    }

    public function test_update_success() {
        $data = [
            'success' => true,
            'data' => [
                'lead_id' => '65007347',
            ],
        ];
        $httpClient = $this->getHttpClient(200, json_encode($data));

        $this->client->setHttpClient($httpClient);

        $result = $this->client->lead->update($this->leadInfo);

        $this->assertEquals($result['lead_id'], '65007347');
    }

    public function test_update_failure() {
        $error = [
            'success' => false,
            'code' => 5001,
            'text' => 'Auth token is invalid',
        ];
        $httpClient = $this->getHttpClient(200, json_encode($error));

        $this->client->setHttpClient($httpClient);

        $this->expectExceptionMessage('Auth token is invalid');

        $this->client->lead->update($this->leadInfo);
    }

    private function getHttpClient($status, $body = null)
    {
        $mock = new MockHandler([new Response($status, [], $body)]);
        $handler = HandlerStack::create($mock);
        $client = new HttpClient(['handler' => $handler]);

        return $client;
    }
}
