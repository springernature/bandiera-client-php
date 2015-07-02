<?php
namespace Nature\Bandiera\Http;

use GuzzleHttp\Client;

class ConnectionException extends \Exception {}

class GuzzleClient
{
    private $client = null;

    public function __construct($domain)
    {
        $this->client = new Client(array('base_uri' => $domain));
    }

    public function getUrlContent($url, $params)
    {
        try {
            $body = $this->client->get($url, ['query' => $params])
                ->getBody();
        } catch (\Exception $e) {
            throw new ConnectionException('Could not connect to Bandiera Server.');
        }

        return $body;
    }
}
