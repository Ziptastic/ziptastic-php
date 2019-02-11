<?php

use function GuzzleHttp\Psr7\stream_for;
use GuzzleHttp\Psr7\Response;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Mock\Client as MockClient;
use PHPUnit\Framework\TestCase;

use Ziptastic\Client;

class ClientTest extends TestCase
{
    private $client;

    private $stub = [
        'county' => 'Macomb',
        'city' => 'Clinton Township',
        'state' => 'Michigan',
        'state_short' => 'MI',
        'postal_code' => '48038',
        'latitude' => 42.5868882,
        'longitude' => -82.9195514,
        'timezone' => 'America/Detroit'
    ];

    public function setUp()
    {
        $http = new MockClient;
        $response = new Response(200, [], stream_for(json_encode([$this->stub, $this->stub])));
        $http->setDefaultResponse($response);

        $this->client = new Client($http, MessageFactoryDiscovery::find(), '123');
    }

    public function testForward()
    {
        $l = $this->client->forward(48038);

        $this->assertEquals($this->stub['city'], $l[0]['city']);
    }

    public function testReverse()
    {
        $l = $this->client->reverse(100.10, 200.20, 1);

        $this->assertEquals($this->stub['city'], $l[0]['city']);
    }

    public function testCollection()
    {
        $l = $this->client->forward(48038);

        $this->assertEquals(2, count($l));
        $this->assertEquals($this->stub['city'], $l[0]['city']);
        $this->assertEquals($this->stub['city'], $l[1]['city']);
    }

    public function testStatic()
    {
        $client = Client::create('123');
        $this->assertInstanceOf(Client::class, $client);
    }
}
