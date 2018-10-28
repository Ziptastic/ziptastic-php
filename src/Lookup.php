<?php

namespace Ziptastic\Ziptastic;

use Ziptastic\Ziptastic\Service\CurlService;
use Ziptastic\Ziptastic\Service\ServiceInterface;

class Lookup
{
    const ZIPTASTIC_LOOKUP_URL = 'https://zip.getziptastic.com/v3/%s/%s';

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
     * @param ?string $apiKey API Key (For non-free accounts)
     * @param ?string $countryCode 2-character country code. Currently only supports "US"
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
     * @param ?string $apiKey
     * @param ?string $countryCode
     * @return Lookup
     */
    public static function create(string $apiKey = null, string $countryCode = 'US'): self
    {
        $service = new CurlService;
        return new self($service, $apiKey, $countryCode);
    }

    /**
     * Get information on given $zipCode
     * @param  string           $zipCode
     * @return LookupModel[]
     */
    public function lookup($zipCode): array
    {
        $url = sprintf(self::ZIPTASTIC_LOOKUP_URL, $this->countryCode, (string) $zipCode);
        $res = $this->service->get($url, $this->apiKey);

        $collection = [];
        foreach ($res as $result) {
            $collection[] = new LookupModel($result);
        }

        return $collection;
    }
}
