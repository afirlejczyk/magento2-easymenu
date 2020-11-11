<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Test\Unit\Model;

use AMF\EasyMenu\Model\Item\UrlBuilderInterfaceFactory;
use AMF\EasyMenu\Model\Item\UrlProvider\Factory;
use AMF\EasyMenu\Model\Item\UrlProviderInterface;
use PHPUnit\Framework\TestCase;
use Magento\Framework\ObjectManagerInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class FactoryTest extends TestCase
{
    /** @var ObjectManagerInterface|\PHPUnit\Framework\MockObject\MockObject  */
    private $objectManagerMock;

    /** @var UrlProviderInterface|\PHPUnit\Framework\MockObject\MockObject  */
    private $urlProviderMock;

    /** @var Factory */
    private $factory;

    protected function setUp()
    {
        $this->objectManagerMock = $this->createMock(ObjectManagerInterface::class);
        $this->urlProviderMock = $this->createMock(UrlProviderInterface::class);
        $this->factory = new Factory($this->objectManagerMock);
    }

    public function testCanGetUrlProviderInterface()
    {
        $className = 'ClassName';
        $this->objectManagerMock->method('get')->with($className)->willReturn($this->urlProviderMock);
        self::assertEquals($this->urlProviderMock, $this->factory->create($className));
    }

    public function testCannotGetUrlProviderInterface()
    {
        $className = 'ClassName';

        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage(sprintf('%s doesnt\t implement %s', $className, UrlProviderInterface::class));

        $this->factory->create($className);
    }
}
