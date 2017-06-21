<?php

use Ziptastic\Ziptastic\LookupModel;

class LookupModelTest extends PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $data = [
            'county' => 'Macomb',
            'city' => 'Clinton Township',
            'state' => 'Michigan',
            'state_short' => 'MI',
            'postal_code' => '48038',
            'latitude' => 42.5868882,
            'longitude' => -82.9195514,
            'timezone' => 'America/Detroit'
        ];

        $model = new LookupModel($data);

        $this->assertEquals($model->county(), $data['county']);
        $this->assertEquals($model->city(), $data['city']);
        $this->assertEquals($model->state(), $data['state']);
        $this->assertEquals($model->stateShort(), $data['state_short']);
        $this->assertEquals($model->postalCode(), $data['postal_code']);
        $this->assertEquals($model->latitude(), $data['latitude']);
        $this->assertEquals($model->longitude(), $data['longitude']);

        $this->assertInstanceOf('DateTimeZone', $model->timezone());
        $this->assertEquals($model->timezone()->getName(), 'America/Detroit');
    }

    public function testNullConstructor()
    {
        $model = new LookupModel([]);
        $this->assertNull($model->county());
        $this->assertNull($model->city());
        $this->assertNull($model->state());
        $this->assertNull($model->stateShort());
        $this->assertNull($model->postalCode());
        $this->assertNull($model->latitude());
        $this->assertNull($model->longitude());
        $this->assertNull($model->timezone());
    }

    public function testInvalidTimezoneReturnsNull()
    {
        // as of 6/21/17, searching zipcode 59018 returns timezone 'n/a'
        // this results in a warning at runtime because
        // the lookup model expected null or a valid timezone only
        $data = [
            'county' => 'Park',
            'city' => 'Clyde Park',
            'state' => 'Michigan',
            'state_short' => 'MT',
            'postal_code' => '59018',
            'latitude' => 45.8341,
            'longitude' => -110.6222,
            'timezone' => 'n/a'
        ];

        $model = new LookupModel($data);
        $this->assertNull($model->timezone());
    }
}