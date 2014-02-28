<?php
namespace spec\Nature\Bandiera;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClientSpec extends ObjectBehavior
{
    function let($http)
    {
        $http->beADoubleOf('Nature\Bandiera\Http\GuzzleClient');
        $this->beConstructedWith('localhost', $http);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Nature\Bandiera\Client');
    }

    function it_should_get_an_enabled_feature($http)
    {
        $json = '{"features":[{"group":"foo","name":"bar","description":"baz","enabled":true}]}';

        $http->getUrlContent('/api/v1/groups/foo/features/bar')
            ->willReturn($json);

        $this->isEnabled('foo', 'bar')->shouldReturn(true);
    }

    function it_should_get_a_disabled_feature($http)
    {
        $json = '{"features":[{"group":"foo","name":"bar","description":"baz","enabled":false}]}';

        $http->getUrlContent('/api/v1/groups/foo/features/bar')
            ->willReturn($json);

        $this->isEnabled('foo', 'bar')->shouldReturn(false);
    }

    function it_should_all_the_features_for_a_group($http)
    {
        $json = '{"features":[{"group":"foo","name":"bar","description":"baz","enabled":false}]}';

        $http->getUrlContent('/api/v1/groups/foo/features')
            ->willReturn($json);

        $this->getFeaturesForGroup('foo')->shouldReturn(array(
            0 => array(
                'group' => 'foo',
                'name'  => 'bar',
                'description' => 'baz',
                'enabled' => false
            )
        ));
    }

    function it_should_return_empty_array_if_group_does_not_exist($http)
    {
        $http->getUrlContent('/api/v1/groups/bar/features')
            ->willReturn(false);

        $this->getFeaturesForGroup('bar')->shouldReturn(array());
    }

    function it_should_get_all($http)
    {
        $http->getUrlContent('/api/v1/all')
            ->willReturn('{"groups":[{"name":"foo","features":[{"group":"foo","name":"bar","description":"zaz","enabled":true}]}]}');

        $this->getAll()->shouldReturn(
            array('groups' => array(
                0 => array(
                    'name' => 'foo',
                    'features' => array(
                    0 => array(
                        'group' => 'foo',
                        'name' => 'bar',
                        'description' => 'zaz',
                        'enabled' => true
                    )
                )
            )))
        );
    }

    function it_should_raise_exception_on_getAll_error($http)
    {
        $http->getUrlContent('/api/v1/all')
            ->willThrow('\Nature\Bandiera\Http\ConnectionException');

        $this->shouldThrow('\Nature\Bandiera\Http\ConnectionException')->during('getAll');
    }

    function it_should_return_false_on_getFeature_error($http)
    {
        $http->getUrlContent('/api/v1/groups/bar/features/foo')
            ->willThrow('\Nature\Bandiera\Http\ConnectionException');

        $this->getFeature('bar', 'foo')->shouldReturn(array(
            'group' => 'bar',
            'name'  => 'foo',
            'description' => '',
            'enabled' => false
        ));
    }
}
