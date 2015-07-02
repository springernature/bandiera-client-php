<?php
namespace Nature\Bandiera\Http;

use GuzzleHttp\Client;

class ConnectionException extends \Exception {}

class GuzzleClient
{
    private $client = null;

    public function __construct($domain)
    {
        $this->client = new Client(['base_uri' => $domain]);
    }

    public function getUrlContent($url, $params)
    {
        if ($params !== []) {
            $params = ['query' => $params];
        }

        try {
            $body = $this->client->get($url, $params)
                ->getBody();
        } catch (\Exception $e) {
            throw new ConnectionException('Could not connect to Bandiera Server.');
        }

        return $body;
    }
}
