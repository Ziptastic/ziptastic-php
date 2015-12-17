<?php

use Ziptastic\Ziptastic\Lookup;

class LookupTest extends PHPUnit_Framework_TestCase
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

    public function testLookup()
    {
        $res = function() {
            return [$this->stub];
        };

        $service = new servicestub($res);

        $lookup = new Lookup($service, '123');
        $l = $lookup->lookup(48038);

        $this->assertEquals(1, count($l));
        $this->assertEquals($this->stub['city'], $l[0]->city());
    }

    public function testCollection()
    {
        $res = function() {
            return [$this->stub, $this->stub];
        };

        $service = new servicestub($res);

        $lookup = new Lookup($service, '123');
        $l = $lookup->lookup(48038);

        $this->assertEquals(2, count($l));
        $this->assertEquals($this->stub['city'], $l[0]->city());
        $this->assertEquals($this->stub['city'], $l[1]->city());
    }

    public function testStatic()
    {
        $lookup = Lookup::create('123');
        $this->assertInstanceOf('Ziptastic\\Ziptastic\\Lookup', $lookup);
    }
}

class servicestub implements Ziptastic\Ziptastic\Service\ServiceInterface
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