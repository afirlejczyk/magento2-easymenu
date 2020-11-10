<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Test\Unit\Model;

use AMF\EasyMenu\Exception\UrlProviderNotExistException;
use AMF\EasyMenu\Model\Item\UrlProvider\Factory;
use AMF\EasyMenu\Model\Item\UrlProvider\Pool;
use AMF\EasyMenu\Model\Item\UrlProviderInterface;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class PoolTest extends TestCase
{
    /** @var Factory */
    private $factoryMock;

    /** @var UrlProviderInterface|\PHPUnit\Framework\MockObject\MockObject  */
    private $urlProviderMock;

    protected function setUp()
    {
        $this->urlProviderMock = $this->createMock(UrlProviderInterface::class);
        $this->factoryMock = $this->createMock(Factory::class);
    }

    public function testGetExistingUrlProvider()
    {
        $typeName = 'cms';
        $typeClass = 'cmsProviderClass';

        $pool = new Pool($this->factoryMock, [$typeName => $typeClass]);

        $this->factoryMock->method('create')
            ->with($typeClass)
            ->willReturn($this->urlProviderMock);

        $this->assertEquals($this->urlProviderMock, $pool->get($typeName));
    }

    public function testCannotGetUrlProviderInterface()
    {
        $typeName = 'cms';
        $typeClass = 'cmsProviderClass';

        $pool = new Pool($this->factoryMock, []);

        $this->factoryMock->method('create')
            ->with($typeClass)
            ->willReturn($this->urlProviderMock);

        $this->expectException(UrlProviderNotExistException::class);
        $this->expectExceptionMessage(sprintf('Url provider for %s wasn\'t found', $typeName));

        $this->assertEquals($this->urlProviderMock, $pool->get($typeName));
    }
}
