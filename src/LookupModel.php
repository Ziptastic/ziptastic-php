<?php namespace Ziptastic\Ziptastic;

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
        $this->county = $this->getOrNull('county', $lookup);
        $this->city = $this->getOrNull('city', $lookup);
        $this->state = $this->getOrNull('state', $lookup);
        $this->stateShort = $this->getOrNull('state_short', $lookup);
        $this->postalCode = $this->getOrNull('postal_code', $lookup);
        $this->latitude = $this->getOrNull('latitude', $lookup);
        $this->longitude = $this->getOrNull('longitude', $lookup);
        $timezone = $this->getOrNull('timezone', $lookup);
        if (!is_null($timezone)) {
            $this->timezone = new DateTimeZone($timezone);
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

    private function getOrNull($key, array $data)
    {
        return (isset($data[$key])) ? $data[$key] : null;
    }
}
