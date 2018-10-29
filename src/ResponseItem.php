<?php

declare(strict_types=1);

namespace Ziptastic;

/**
 * Represents a Ziptastic API response item.
 */
class ResponseItem
{
    /**
     * @param  array
     */
    public function __construct(array $data)
    {
        $this->data = $data + [
            'county' => null,
            'city' => null,
            'state' => null,
            'state_short' => null,
            'postal_code' => null,
            'latitude' => null,
            'longitude' => null,
            'timezone' => null,
        ];

        if ($this->data['timezone']) {
            // If the timezone is not valid, keep null.
            try {
                $this->data['timezone'] = new \DateTimeZone($this->data['timezone']);
            } catch (\Exception $e) {
                $this->data['timezone'] = null;
            }
        }
    }

    /**
     * @return string
     */
    public function county(): ?string
    {
        return $this->data['county'];
    }

    /**
     * @return string
     */
    public function city(): ?string
    {
        return $this->data['city'];
    }

    /**
     * @return string
     */
    public function state(): ?string
    {
        return $this->data['state'];
    }

    /**
     * @return string
     */
    public function stateShort(): ?string
    {
        return $this->data['state_short'];
    }

    /**
     * @return string
     */
    public function postalCode(): ?string
    {
        return $this->data['postal_code'];
    }

    /**
     * @return float
     */
    public function latitude(): ?float
    {
        return $this->data['latitude'];
    }

    /**
     * @return float
     */
    public function longitude(): ?float
    {
        return $this->data['longitude'];
    }

    /**
     * @return \DateTimeZone
     */
    public function timezone(): ?\DateTimeZone
    {
        return $this->data['timezone'];
    }
}
