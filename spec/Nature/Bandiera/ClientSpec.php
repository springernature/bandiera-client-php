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
        $json = '{"response": true}';

        $http->getUrlContent('/api/v2/groups/foo/features/bar', [])
            ->willReturn($json);

        $this->isEnabled('foo', 'bar')->shouldReturn(true);
    }

    function it_should_get_a_disabled_feature($http)
    {
        $json = '{"response": false}';

        $http->getUrlContent('/api/v2/groups/foo/features/bar', [])
            ->willReturn($json);

        $this->isEnabled('foo', 'bar')->shouldReturn(false);
    }

    function it_should_all_the_features_for_a_group($http)
    {
        $json = '{"response":[{"foo": true}]}';

        $http->getUrlContent('/api/v2/groups/foo/features', [])
            ->willReturn($json);

        $this->getFeaturesForGroup('foo')->shouldReturn(array(
            0 => array('foo' => true)
        ));
    }

    function it_should_return_empty_array_if_group_does_not_exist($http)
    {
        $http->getUrlContent('/api/v2/groups/bar/features', [])
            ->willReturn(false);

        $this->getFeaturesForGroup('bar')->shouldReturn(array());
    }

    function it_should_get_all($http)
    {
        $http->getUrlContent('/api/v2/all', [])
            ->willReturn('{"response":{"foo":{"bar":true},"bar":{"foo":false}}}');

        $this->getAll()->shouldReturn(
            array(
                'foo' => array('bar' => true),
                'bar' => array('foo' => false)
            )
        );
    }

    function it_should_raise_exception_on_getAll_error($http)
    {
        $http->getUrlContent('/api/v2/all', [])
            ->willThrow('\Nature\Bandiera\Http\ConnectionException');

        $this->shouldThrow('\Nature\Bandiera\Http\ConnectionException')->during('getAll');
    }

    function it_should_return_false_on_getFeature_error($http)
    {
        $http->getUrlContent('/api/v2/groups/bar/features/foo', [])
            ->willThrow('\Nature\Bandiera\Http\ConnectionException');

        $this->getFeature('bar', 'foo', [])->shouldReturn(array(
            'group' => 'bar',
            'name'  => 'foo',
            'description' => '',
            'enabled' => false
        ));
    }
}
