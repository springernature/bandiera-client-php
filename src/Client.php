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

    public function getAll()
    {
        $return = array();
        $features = $this->get('/api/v2/all');

        if (isset($features['response'])) {
            $return = $features['response'];
        }

        return $return;
    }

    public function isEnabled($group, $feature)
    {
        $feature = $this->getFeature($group, $feature);

        return $feature['enabled'];
    }

    public function getFeaturesForGroup($group)
    {
        $return = array();
        $feature = $this->get('/api/v2/groups/' . $group . '/features');

        if (isset($feature['response'])) {
            $return = $feature['response'];
        }

        return $return;
    }

    public function getFeature($group, $feature, $params = array())
    {
        $return_feature = array(
            'group'       => $group,
            'name'        => $feature,
            'description' => '',
            'enabled'     => false
        );

        try {
            $feature = $this->get('/api/v2/groups/' . $group . '/features/' . $feature);
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

    private function get($uri)
    {
        $default_return = array();

        $json = json_decode($this->http->getUrlContent($uri), true);

        if (null !== $json) {
            $default_return = $json;
        }

        return $default_return;
    }
}
