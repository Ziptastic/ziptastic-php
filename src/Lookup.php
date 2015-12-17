<?php namespace Ziptastic\Ziptastic;

use Ziptastic\Ziptastic\Service\CurlService;
use Ziptastic\Ziptastic\Service\ServiceInterface;

class Lookup
{
    const ZIPTASTIC_LOOKUP_URL = 'https://zip.getziptastic.com/v3/%s/%d';

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
     * @param  ServiceInterface $service
     * @param  string  $apiKey      API Key (For non-free accounts)
     * @param  string  $countryCode 2-character country code. Currently only supports "US"
     */
    public function __construct(ServiceInterface $service, $apiKey = null, $countryCode = 'US')
    {
        $this->service = $service;
        $this->apiKey = $apiKey;
        $this->countryCode = $countryCode;
    }

    public static function create($apiKey = null, $countryCode = 'US')
    {
        $service = new CurlService;
        return new self($service, $apiKey, $countryCode);
    }

    /**
     * Get information on given $zipCode
     * @param  int                $zipCode
     * @return array[LookupModel]
     */
    public function lookup($zipCode)
    {
        $url = sprintf(self::ZIPTASTIC_LOOKUP_URL, $this->countryCode, $zipCode);
        $res = $this->service->get($url, $this->apiKey);

        $collection = [];
        foreach ($res as $result) {
            $collection[] = new LookupModel($result);
        }

        return $collection;
    }
}