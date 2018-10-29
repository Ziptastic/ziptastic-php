<?php namespace Ziptastic\Service;

use Ziptastic\Exception;

class CurlService implements ServiceInterface
{
    public function get($url, $apiKey)
    {
        $handle = $this->curl_init();
        $this->curl_setopt($handle, CURLOPT_URL, $url);
        $this->curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $this->curl_setopt($handle, CURLOPT_HTTPHEADER, [
            sprintf("x-key: %s", $apiKey)
        ]);

        $response = $this->curl_exec($handle);

        $res = json_decode(trim($response), true);
        $statusCode = $this->curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $this->curl_close($handle);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception(sprintf(
                'Could not parse response as json. Reason: %s',
                json_last_error_msg()
            ));
        }

        if ($statusCode !== 200 && isset($res['message'])) {
            throw new Exception($res['message']);
        }

        else if ($statusCode !== 200) {
            throw new Exception('An error occurred');
        }

        return $res;
    }

    /** These are for mocking in the unit tests **/

    /**
     * @codeCoverageIgnore
     * @SuppressWarnings(PHPMD)
     */
    protected function curl_init()
    {
        return curl_init();
    }

    /**
     * @codeCoverageIgnore
     * @SuppressWarnings(PHPMD)
     */
    protected function curl_setopt($handle, $name, $opt)
    {
        return curl_setopt($handle, $name, $opt);
    }

    /**
     * @codeCoverageIgnore
     * @SuppressWarnings(PHPMD)
     */
    protected function curl_getinfo($handle, $name)
    {
        return curl_getinfo($handle, $name);
    }

    /**
     * @codeCoverageIgnore
     * @SuppressWarnings(PHPMD)
     */
    protected function curl_exec($handle)
    {
        return curl_exec($handle);
    }

    /**
     * @codeCoverageIgnore
     * @SuppressWarnings(PHPMD)
     */
    protected function curl_close($handle)
    {
        return curl_close($handle);
    }
}
