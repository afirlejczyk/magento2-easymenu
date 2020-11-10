<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Test\Unit\Model;

use AMF\EasyMenu\Model\Item\UrlBuilder;
use AMF\EasyMenu\Model\Item\UrlBuilderInterfaceFactory;
use AMF\EasyMenu\Model\Item\UrlResolver;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class UrlResolverTest extends TestCase
{
    private const STORE_ID = 1;

    /** @var ItemInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $item;

    /** @var UrlBuilder|\PHPUnit\Framework\MockObject\MockObject */
    private $urlBuilder;

    /** @var \PHPUnit\Framework\MockObject\MockObject */
    private $urlBuilderFactory;

    /** @var UrlResolver */
    private $urlResolver;

    protected function setUp()
    {
        $this->item = $this->getMockBuilder(ItemInterface::class)->getMock();
        $this->urlBuilder = $this->createMock(UrlBuilder::class);
        $this->urlBuilderFactory = $this->createMock(UrlBuilderInterfaceFactory::class);

        $this->urlResolver = new UrlResolver($this->urlBuilderFactory);
    }

    public function testGetUrlForOneItem()
    {
        $type = 'cms';
        $url = 'www.test.local';
        $itemId = 1;

        $this->item->method('getTypeId')->willReturn($type);
        $this->item->method('getId')->willReturn($itemId);
        $this->item->method('getStoreId')->willReturn(self::STORE_ID);

        $this->urlBuilder
            ->method('getUrlsForActiveItems')
            ->willReturn([$itemId => $url]);

        $this->urlBuilderFactory
            ->method('create')
            ->with(['storeId' => self::STORE_ID])
            ->willReturn($this->urlBuilder);

        $this->assertEquals($url, $this->urlResolver->getUrl($this->item));
    }
}
