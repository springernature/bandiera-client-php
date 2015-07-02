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
        return $this->get('/api/v1/all');
    }

    public function isEnabled($group, $feature)
    {
        $feature = $this->getFeature($group, $feature);

        return $feature['enabled'];
    }

    public function getFeaturesForGroup($group)
    {
        $features = $this->get('/api/v1/groups/' . $group . '/features');

        if (isset($features['features'])) {
            $features = $features['features'];
        }

        return $features;
    }

    public function getFeature($group, $feature)
    {
        $return_feature = array(
            'group'       => $group,
            'name'        => $feature,
            'description' => '',
            'enabled'     => false
        );

        try {
            $features = $this->get('/api/v1/groups/' . $group . '/features/' . $feature);
        } catch (ConnectionException $e) {
            return $return_feature;
        }

        if (isset($features['features'])) {
            foreach ($features['features'] as $f) {
                if ($feature === $f['name']) {
                    $return_feature = $f;
                }
            }
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
