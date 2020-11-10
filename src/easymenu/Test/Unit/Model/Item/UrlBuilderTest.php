<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Test\Unit\Model;

use AMF\EasyMenu\Model\Item\Command\DeleteInterface;
use AMF\EasyMenu\Model\Item\Command\GetInterface;
use AMF\EasyMenu\Model\Item\Command\GetListInterface;
use AMF\EasyMenu\Model\Item\Command\SaveInterface;
use AMF\EasyMenu\Model\Item\UrlBuilder;
use AMF\EasyMenu\Model\Item\UrlProvider\Pool;
use AMF\EasyMenu\Model\Item\UrlProviderInterface;
use AMF\EasyMenu\Model\ItemRepository;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use AMF\EasyMenuApi\Api\Data\ItemSearchResultInterface;
use AMF\EasyMenuApi\Model\GetAllItemsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class UrlBuilderTest extends TestCase
{
    private const STORE_ID = 1;

    /** @var UrlProviderInterface|\PHPUnit\Framework\MockObject\MockObject */
    private $urlProvider;

    /** @var ItemInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $item;

    /** @var ItemSearchResultInterface|\PHPUnit_Framework_MockObject_MockObject */
    private $searchResult;

    /** @var GetAllItemsInterface */
    private $getAllItems;

    /** @var Pool */
    private $pool;

    /** @var UrlBuilder */
    private $urlBuilder;

    protected function setUp()
    {
        $this->searchResult = $this->getMockBuilder(ItemSearchResultInterface::class)->getMock();
        $this->item = $this->getMockBuilder(ItemInterface::class)->getMock();

        $this->pool = $this->createMock(Pool::class);
        $this->getAllItems = $this->createMock(GetAllItemsInterface::class);
        $this->urlProvider = $this->createMock(UrlProviderInterface::class);

        $this->urlBuilder = new UrlBuilder(
            $this->getAllItems,
            $this->pool,
            self::STORE_ID
        );
    }

    public function testGetUrlForOneItem()
    {
        $type = 'cms';
        $itemId = 1;

        $this->item->method('getTypeId')->willReturn($type);
        $this->item->method('getId')->willReturn($itemId);

        $this->searchResult->method('getItems')->willReturn([$this->item]);
        $this->getAllItems->method('execute')->with(1, true)->willReturn($this->searchResult);
        $this->pool->method('get')->with($type)->willReturn($this->urlProvider);

        $result = [
            $itemId => 'www.test.local'
        ];

        $this->urlProvider
            ->expects($this->once())
            ->method('loadAll')
            ->with(
                self::STORE_ID,
                ...[$this->item]
            )
            ->willReturn(
                $result
            );

        $this->assertEquals($result, $this->urlBuilder->getUrlsForActiveItems());
    }
}
