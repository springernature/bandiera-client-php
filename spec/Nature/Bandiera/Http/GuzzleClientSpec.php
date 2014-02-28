<?php

namespace spec\Nature\Bandiera\Http;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GuzzleClientSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('localhost');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Nature\Bandiera\Http\GuzzleClient');
    }

    function it_should_bring_something()
    {
        $this->getUrlContent('http://www.google.com')->shouldNotBe('');
    }

    function it_should_raise_exception_on_error()
    {
        $this->shouldThrow('\Nature\Bandiera\Http\ConnectionException')
            ->during('getUrlContent', array('http://invalid_domain/foo/bar'));
    }
}
