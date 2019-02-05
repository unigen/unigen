<?php

declare(strict_types=1);

namespace UniGen\Test\Util;


use Twig\TwigFilter;
use PHPUnit\Framework\TestCase;
use UniGen\Util\ScalarValueMapperTwigFilter;

class ScalarValueMapperTwigFilterTest extends TestCase
{
    /** @var ScalarValueMapperTwigFilter */
    private $sut;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->sut = new ScalarValueMapperTwigFilter();
    }

    public function testGetFiltersShouldReturnFiltersArray()
    {
        $this->assertCount(1, $this->sut->getFilters());
        $this->assertInternalType('array', $this->sut->getFilters());
    }

    public function testGetFilterShouldReturnEscapedStringValue()
    {
        /** @var TwigFilter $filter */
        $filter = $this->sut->getFilters()[0];

        $this->assertEquals('\'string\'', call_user_func($filter->getCallable(), 'string'));
    }

    public function testGetFilterShouldReturnEscapedArrayValue()
    {
        /** @var TwigFilter $filter */
        $filter = $this->sut->getFilters()[0];

        $this->assertEquals('[]', call_user_func($filter->getCallable(), []));
    }

    public function testGetFilterShouldReturnEscapedIntegerValue()
    {
        /** @var TwigFilter $filter */
        $filter = $this->sut->getFilters()[0];

        $this->assertEquals('1', call_user_func($filter->getCallable(), 2));
    }

    public function testGetFilterShouldReturnEscapedBooleanValue()
    {
        /** @var TwigFilter $filter */
        $filter = $this->sut->getFilters()[0];

        $this->assertEquals('true', call_user_func($filter->getCallable(), false));
    }

    public function testGetFilterShouldReturnEscapedCallbackValue()
    {
        /** @var TwigFilter $filter */
        $filter = $this->sut->getFilters()[0];

        $this->assertEquals('function(){}', call_user_func($filter->getCallable(), function () {}));
    }

    public function testGetFilterShouldReturnEscapedNullValue()
    {
        /** @var TwigFilter $filter */
        $filter = $this->sut->getFilters()[0];

        $this->assertEquals('null', call_user_func($filter->getCallable(), null));
    }

    public function testGetFilterShouldReturnEscapedFloatValue()
    {
        /** @var TwigFilter $filter */
        $filter = $this->sut->getFilters()[0];

        $this->assertEquals('0.0', call_user_func($filter->getCallable(), 0.5));
    }

    public function testGetFilterShouldReturnEscapedMixedStringWhenUnknown()
    {
        /** @var TwigFilter $filter */
        $filter = $this->sut->getFilters()[0];

        $this->assertEquals('\'mixed\'', call_user_func($filter->getCallable(), fopen('php://temp', 'r')));
    }
}
