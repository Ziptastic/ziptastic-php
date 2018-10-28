<?php

use PHPUnit\Framework\TestCase;
use Ziptastic\Ziptastic\Exception;

class CurlServiceTest extends TestCase
{
    public function testGet()
    {
        $res = function() { return json_encode(['foo' => 'bar']); };
        $service = new curlservicestub($res, 200);

        $url = 'http://johnpedrie.com';
        $l = $service->get($url, '123');
        $this->assertEquals($service->opts[CURLOPT_URL], $url);
        $this->assertEquals($service->opts[CURLOPT_HTTPHEADER], [
            'x-key: 123'
        ]);

        $this->assertEquals($l['foo'], 'bar');
    }


    public function testGetMalformed()
    {
        $this->expectException(
            Exception::class,
            'Could not parse response as json'
        );

        $res = function() { return json_encode(['foo' => 'bar']) . 'foo'; };
        $service = new curlservicestub($res, 200);

        $url = 'http://johnpedrie.com';
        $l = $service->get($url, '123');
    }

    public function testGetInvalidStatusWithMessage()
    {
        $this->expectException(
            Exception::class,
            'bad'
        );

        $res = function() { return json_encode(['foo' => 'bar', 'message' => 'bad' ]); };
        $service = new curlservicestub($res, 500);

        $url = 'http://johnpedrie.com';
        $l = $service->get($url, '123');
    }

    public function testGetInvalidStatusWithoutMessage()
    {
        $this->expectException(
            Exception::class,
            'An error occurred'
        );

        $res = function() { return json_encode(['foo' => 'bar' ]); };
        $service = new curlservicestub($res, 500);

        $url = 'http://johnpedrie.com';
        $l = $service->get($url, '123');
    }
}

class curlservicestub extends Ziptastic\Ziptastic\Service\CurlService
{
    private $res;
    private $statusCode;
    public $opts = [];

    public function __construct(callable $res, $statusCode)
    {
        $this->res = $res;
        $this->statusCode = $statusCode;
    }

    protected function curl_init()
    {
        return;
    }

    protected function curl_setopt($ch, $name, $opt)
    {
        $this->opts[$name] = $opt;
        return;
    }

    protected function curl_getinfo($ch, $name)
    {
        return $this->statusCode;
    }

    protected function curl_exec($ch)
    {
        return call_user_func($this->res);
    }

    protected function curl_close($ch)
    {
        return;
    }
}
