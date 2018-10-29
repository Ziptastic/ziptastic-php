<?php

use PHPUnit\Framework\TestCase;
use Ziptastic\Client;
use Ziptastic\Service\ServiceInterface;

class ClientTest extends TestCase
{
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

    public function testFormward()
    {
        $res = function() {
            return [$this->stub];
        };

        $service = new servicestub($res);

        $client = new Client($service, '123');
        $l = $client->forward(48038);

        $this->assertEquals(1, count($l));
        $this->assertEquals($this->stub['city'], $l[0]->city());
    }

    public function testReverse()
    {
        $res = function() {
            return [$this->stub];
        };

        $service = new servicestub($res);

        $client = new Client($service, '123');
        $l = $client->reverse(100.10, 200.20, 1);

        $this->assertEquals(1, count($l));
        $this->assertEquals($this->stub['city'], $l[0]->city());
    }

    public function testCollection()
    {
        $res = function() {
            return [$this->stub, $this->stub];
        };

        $service = new servicestub($res);

        $client = new Client($service, '123');
        $l = $client->forward(48038);

        $this->assertEquals(2, count($l));
        $this->assertEquals($this->stub['city'], $l[0]->city());
        $this->assertEquals($this->stub['city'], $l[1]->city());
    }

    public function testStatic()
    {
        $client = Client::create('123');
        $this->assertInstanceOf(client::class, $client);
    }
}

class servicestub implements ServiceInterface
{
    private $res;

    public function __construct(callable $res)
    {
        $this->res = $res;
    }

    public function get($url, $headers)
    {
        return call_user_func($this->res);
    }
}
