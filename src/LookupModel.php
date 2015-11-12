<?php namespace Ziptastic;

use DateTimeZone;

class LookupModel
{
    /**
     * @var string
     */
    private $county;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $state;

    /**
     * @var string
     */
    private $stateShort;

    /**
     * @var int
     */
    private $postalCode;

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

    /**
     * @var DateTimeZone
     */
    private $timezone;

    /**
     * @param  array
     */
    public function __construct(array $lookup)
    {
        $this->county = (isset($lookup['county'])) ? $lookup['county'] : null;
        $this->city = (isset($lookup['city'])) ? $lookup['city'] : null;
        $this->state = (isset($lookup['state'])) ? $lookup['state'] : null;
        $this->stateShort = (isset($lookup['state_short'])) ? $lookup['state_short'] : null;
        $this->postalCode = (isset($lookup['postal_code'])) ? $lookup['postal_code'] : null;
        $this->latitude = (isset($lookup['latitude'])) ? $lookup['latitude'] : null;
        $this->longitude = (isset($lookup['longitude'])) ? $lookup['longitude'] : null;
        $tz = (isset($lookup['timezone'])) ? $lookup['timezone'] : null;
        if (!is_null($tz)) {
            $this->tz = new DateTimeZone($tz);
        }
    }

    /**
     * @return string
     */
    public function county()
    {
        return $this->county;
    }

    /**
     * @return string
     */
    public function city()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function state()
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function stateShort()
    {
        return $this->stateShort;
    }

    /**
     * @return int
     */
    public function postalCode()
    {
        return $this->postalCode;
    }

    /**
     * @return float
     */
    public function latitude()
    {
        return $this->latitude;
    }

    /**
     * @return float
     */
    public function longitude()
    {
        return $this->longitude;
    }

    /**
     * @return DateTimeZone
     */
    public function timezone()
    {
        return $this->timezone;
    }
}