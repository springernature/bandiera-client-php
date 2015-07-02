<?php
namespace Nature\Bandiera;

use Nature\Bandiera\Http\GuzzleClient;
use Nature\Bandiera\Http\ConnectionException;

class Client
{
    private $http = null;

    public function __construct($domain, $http = null)
    {
        $this->http = $http ?: new GuzzleClient($domain);
    }

    public function getAll($params = [])
    {
        $return = [];
        $features = $this->get('/api/v2/all', $params);

        if (isset($features['response'])) {
            $return = $features['response'];
        }

        if (isset($features['warning'])) {
            $return['warning'] = $features['warning'];
        }

        return $return;
    }

    public function isEnabled($group, $feature, $params = [])
    {
        $feature = $this->getFeature($group, $feature, $params);

        return $feature['enabled'];
    }

    public function getFeaturesForGroup($group, $params = [])
    {
        $return = [];
        $feature = $this->get('/api/v2/groups/' . $group . '/features', $params);

        if (isset($feature['response'])) {
            $return = $feature['response'];
        }

        return $return;
    }

    public function getFeature($group, $feature, $params = [])
    {
        $return_feature = [
            'group'       => $group,
            'name'        => $feature,
            'description' => '',
            'enabled'     => false
        ];

        try {
            $feature = $this->get('/api/v2/groups/' . $group . '/features/' . $feature, $params);
        } catch (ConnectionException $e) {
            return $return_feature;
        }

        if (isset($feature['response'])) {
            $return_feature['enabled'] = $feature['response'];
        }

        if (isset($feature['warning'])) {
            $return_feature['warning'] = $feature['warning'];
        }

        return $return_feature;
    }

    private function get($uri, $params)
    {
        $default_return = [];

        $json = json_decode($this->http->getUrlContent($uri, $params), true);

        if (null !== $json) {
            $default_return = $json;
        }

        return $default_return;
    }
}
