<?php

declare(strict_types=1);

namespace Ziptastic;

use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;

/**
 * The Ziptastic API class.
 *
 * Example:
 * ```
 * use Ziptastic\Client;
 *
 * $ziptastic = Client::create($myApiKey);
 * ```
 */
class Client
{
    const ZIPTASTIC_LOOKUP_URL = 'https://zip.getziptastic.com/v3/%s/%s';
    const ZIPTASTIC_REVERSE_URL = 'https://zip.getziptastic.com/v3/reverse/%s/%s/%s';

    /**
     * @var HttpClient;
     */
    private $http;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $countryCode;

    /**
     * @param HttpClient $http An HTTP Client implementation.
     * @param MessageFactory $messageFactory An HTTP message factory implementation.
     * @param string|null $apiKey API Key (For non-free accounts)
     * @param string|null $countryCode 2-character country code. Currently only
     *        supports "US"
     */
    public function __construct(
        HttpClient $http,
        MessageFactory $messageFactory,
        string $apiKey = null,
        string $countryCode = 'US'
    ) {
        $this->http = $http;
        $this->messageFactory = $messageFactory;
        $this->apiKey = $apiKey;
        $this->countryCode = $countryCode;
    }

    /**
     * Create a client instance with the default HTTP handler.
     *
     * Example:
     * ```
     * $ziptastic = Client::create($myApiKey);
     * ```
     *
     * @param string|null $apiKey API Key (For non-free accounts)
     * @param string|null $countryCode 2-character country code. Currently only
     *        supports "US"
     * @return Client
     */
    public static function create(
        string $apiKey = null,
        string $countryCode = 'US'
    ): self {
        $http = HttpClientDiscovery::find();
        $messageFactory = MessageFactoryDiscovery::find();

        return new self($http, $messageFactory, $apiKey, $countryCode);
    }

    /**
     * Lookup locale information by a postal code.
     *
     * Example:
     * ```
     * $result = $ziptastic->forward('48226');
     * foreach ($result as $item) {
     *     echo $item->postalCode() . PHP_EOL;
     * }
     * ```
     *
     * @param string $zipCode The lookup postal code.
     * @return ResponseItem[]
     */
    public function forward(string $postalCode): array
    {
        $url = sprintf(
            self::ZIPTASTIC_LOOKUP_URL,
            $this->countryCode,
            $postalCode
        );

        return $this->request($url);
    }

    /**
     * Lookup locale information by a set of coordinates.
     *
     * Example:
     * ```
     * $result = $ziptastic->reverse(42.331427, -83.0457538, 1000);
     * foreach ($result as $item) {
     *     echo $item->postalCode() . PHP_EOL;
     * }
     * ```
     *
     * @param float $latitude The lookup centerpoint latitude.
     * @param float $longitude The lookup centerpoint longitude.
     * @param integer $radius The search radius, in meters. Defaults to `1000`.
     * @return ResponseItem[]
     */
    public function reverse(float $latitude, float $longitude, int $radius = 1000): array
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
    private function request(string $url): array
    {
        try {
            $res = $this->http->sendRequest(
                $this->messageFactory->createRequest('GET', $url, [
                    'x-key' => $this->apiKey
                ])
            );
        } catch (\Exception $e) {
            throw new Exception('An error occurred: '. $e->getMessage(), $e->getCode(), $e);
        }

        $body = json_decode($res->getBody()->getContents(), true);

        if ($res->getStatusCode() !== 200) {
            $message = isset($body['message'])
                ? $body['message']
                : 'An error occurred';

            throw new Exception($message, $res->getStatusCode());
        }

        $collection = [];
        foreach ($body as $result) {
            $collection[] = new ResponseItem($result);
        }

        return $collection;
    }
}
