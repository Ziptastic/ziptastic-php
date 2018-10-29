<?php

namespace Ziptastic;

use Ziptastic\Service\CurlService;
use Ziptastic\Service\ServiceInterface;

class Client
{
    const ZIPTASTIC_LOOKUP_URL = 'https://zip.getziptastic.com/v3/%s/%s/';
    const ZIPTASTIC_REVERSE_URL = 'https://zip.getziptastic.com/v3/reverse/%s/%s/%s/';

    /**
     * @var ServiceInterface;
     */
    private $service;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $countryCode;

    /**
     * @param ServiceInterface $service
     * @param string|null $apiKey API Key (For non-free accounts)
     * @param string|null $countryCode 2-character country code. Currently only
     *        supports "US"
     */
    public function __construct(
        ServiceInterface $service,
        string $apiKey = null,
        string $countryCode = 'US'
    ) {
        $this->service = $service;
        $this->apiKey = $apiKey;
        $this->countryCode = $countryCode;
    }

    /**
     * Create a client instance with the default service.
     *
     * @param string|null $apiKey
     * @param string|null $countryCode
     * @return Lookup
     */
    public static function create(
        string $apiKey = null,
        string $countryCode = 'US'
    ): self {
        $service = new CurlService;
        return new self($service, $apiKey, $countryCode);
    }

    /**
     * Lookup locale information by a postal code.
     *
     * @param string $zipCode The lookup postal code.
     * @return ResponseItem[]
     */
    public function forward(string $postalCode): array
    {
        $url = sprintf(
            self::ZIPTASTIC_LOOKUP_URL,
            $this->countryCode,
            (string) $postalCode
        );

        return $this->request($url);
    }

    /**
     * Lookup locale information by a set of coordinates.
     *
     * @param float $latitude The lookup centerpoint latitude.
     * @param float $longitude The lookup centerpoint longitude.
     * @param integer $radius The search radius, in meters.
     * @return ResponseItem[]
     */
    public function reverse(float $latitude, float $longitude, int $radius): array
    {
        $url = sprintf(
            self::ZIPTASTIC_REVERSE_URL,
            $latitude,
            $longitude,
            $radius
        );

        return $this->request($url);
    }

    /**
     * Make a request to a given URI with the ziptastic API key.
     *
     * @param string $url
     * @return ResponseItem[]
     */
    private function request(string $url)
    {
        $res = $this->service->get($url, $this->apiKey);

        $collection = [];
        foreach ($res as $result) {
            $collection[] = new ResponseItem($result);
        }

        return $collection;
    }
}
