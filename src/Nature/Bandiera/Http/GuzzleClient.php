<?php
namespace Nature\Bandiera\Http;

use Guzzle\Http\Client;
use Guzzle\Http\EntityBody;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;

class ConnectionException extends \Exception {}

class GuzzleClient
{
    private $client = null;

    public function __construct($domain)
    {
        $this->client = new Client($domain);
    }

    public function getUrlContent($url)
    {
        try {
            $body = $this->client->get($url)
                ->send()
                ->getBody(true);
        } catch (\Guzzle\Http\Exception\CurlException $e) {
            throw new ConnectionException('Could not connect to Bandiera Server.');
        }
    }
}
