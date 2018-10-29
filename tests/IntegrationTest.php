<?php

use PHPUnit\Framework\TestCase;
use Ziptastic\Client;

/**
 * @group integration
 */
class IntegrationTest extends TestCase
{
    private $apiKey;

    public function setUp()
    {
        $this->apiKey = getenv('ZIPTASTIC_API_KEY');

        if (!$this->apiKey) {
            $this->markTestSkipped('No API Key.');
        }
    }

    public function testForwardLookup()
    {
        $lookup = Client::create($this->apiKey);
        $l = $lookup->forward(48038);

        foreach ($l as $model) {
            $this->assertInternalType('string', $model->county());
            $this->assertInternalType('string', $model->city());
            $this->assertInternalType('string', $model->state());
            $this->assertInternalType('string', $model->stateShort());
            $this->assertInternalType('string', $model->postalCode());
            $this->assertInternalType('double', $model->latitude());
            $this->assertInternalType('double', $model->longitude());

            $this->assertInstanceOf(\DateTimeZone::class, $model->timezone());
        }
    }

    public function testReverseLookup()
    {
        $lookup = Client::create($this->apiKey);
        $l = $lookup->reverse(42.331427, -83.0457538, 1000);

        foreach ($l as $model) {
            $this->assertInternalType('string', $model->county());
            $this->assertInternalType('string', $model->city());
            $this->assertInternalType('string', $model->state());
            $this->assertInternalType('string', $model->stateShort());
            $this->assertInternalType('string', $model->postalCode());
            $this->assertInternalType('double', $model->latitude());
            $this->assertInternalType('double', $model->longitude());

            $this->assertInstanceOf(\DateTimeZone::class, $model->timezone());
        }
    }

    /**
     * @expectedException Ziptastic\Exception
     */
    public function testException()
    {
        $lookup = Client::create($this->apiKey);
        $lookup->forward('hello');
    }
}
