<?php

use PHPUnit\Framework\TestCase;
use Ziptastic\Ziptastic\Lookup;

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

    public function testZiptastic()
    {
        $lookup = Lookup::create($this->apiKey);
        $l = $lookup->lookup(48038);

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
}
