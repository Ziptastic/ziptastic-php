<?php namespace Ziptastic\Service;

use Ziptastic\Exception;

class CurlService implements ServiceInterface
{
    public function get($url, $apiKey)
    {
        $ch = $this->curl_init();
        $this->curl_setopt($ch, CURLOPT_URL, $url);
        $this->curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $this->curl_setopt($ch, CURLOPT_HTTPHEADER, [
            sprintf("x-key: %s", $apiKey)
        ]);

        $response = $this->curl_exec($ch);

        $res = json_decode(trim($response), true);
        $statusCode = $this->curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->curl_close($ch);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Could not parse response as json');
        }

        if ($statusCode !== 200) {
            throw new Exception($res['message']);
        }

        return $res;
    }

    /** These are for mocking in the unit tests **/

    protected function curl_init()
    {
        return curl_init();
    }

    protected function curl_setopt($ch, $name, $opt)
    {
        return curl_setopt($ch, $name, $opt);
    }

    protected function curl_getinfo($ch, $name)
    {
        return curl_getinfo($ch, $name);
    }

    protected function curl_exec($ch)
    {
        return curl_exec($ch);
    }

    protected function curl_close($ch)
    {
        return curl_close($ch);
    }
}