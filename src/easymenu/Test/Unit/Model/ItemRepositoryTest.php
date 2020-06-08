<?php

declare(strict_types=1);

namespace AMF\EasyMenuTest\Unit\Model;

use AMF\EasyMenu\Model\Item\Command\DeleteInterface;
use AMF\EasyMenu\Model\Item\Command\GetInterface;
use AMF\EasyMenu\Model\Item\Command\GetListInterface;
use AMF\EasyMenu\Model\Item\Command\SaveInterface;
use AMF\EasyMenu\Model\ItemRepository;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use AMF\EasyMenuApi\Api\Data\ItemSearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ItemRepositoryTest extends TestCase
{
    /**
     * @var SaveInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $commandSave;

    /**
     * @var GetInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $commandGet;

    /**
     * @var GetListInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $commandGetList;

    /**
     * @var DeleteInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $commandDelete;

    /**
     * @var ItemInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $item;

    /**
     * @var ItemSearchResultInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $searchResult;

    /**
     * @var ItemRepository
     */
    private $itemRepository;

    protected function setUp()
    {
        $this->commandSave = $this->getMockBuilder(SaveInterface::class)->getMock();
        $this->commandGet = $this->getMockBuilder(GetInterface::class)->getMock();
        $this->commandGetList = $this->getMockBuilder(GetListInterface::class)->getMock();
        $this->commandDelete = $this->getMockBuilder(DeleteInterface::class)->getMock();
        $this->item = $this->getMockBuilder(ItemInterface::class)->getMock();
        $this->searchResult = $this->getMockBuilder(ItemSearchResultInterface::class)->getMock();

        $this->itemRepository = new ItemRepository(
            $this->commandDelete,
            $this->commandGetList,
            $this->commandGet,
            $this->commandSave
        );
    }

    public function testSave()
    {
        $itemId = 2;
        $this->commandSave
            ->method('execute')
            ->with($this->item)
            ->willReturn($itemId);

        self::assertSame($this->itemRepository->save($this->item), $itemId);
    }

    /**
     * @expectedException \Magento\Framework\Exception\CouldNotSaveException
     * @expectedExceptionMessage Some error
     */
    public function testSaveWithCouldNotSaveException()
    {
        $this->commandSave
            ->method('execute')
            ->with($this->item)
            ->willThrowException(new CouldNotSaveException(__('Some error')));

        $this->itemRepository->save($this->item);
    }

    public function testGet()
    {
        $itemId = 2;
        $this->commandGet
            ->method('execute')
            ->with($itemId)
            ->willReturn($this->item);

        self::assertEquals($this->item, $this->itemRepository->get($itemId));
    }

    /**
     * @expectedException \Magento\Framework\Exception\NoSuchEntityException
     * @expectedExceptionMessage Some error
     */
    public function testGetWithNoSuchEntityException()
    {
        $itemId = 2;
        $this->commandGet
            ->method('execute')
            ->with($itemId)
            ->willThrowException(new NoSuchEntityException(__('Some error')));

        $this->itemRepository->get($itemId);
    }

    public function testGetListWithSearchCriteria()
    {
        $searchCriteria = $this->getMockBuilder(SearchCriteriaInterface::class)->getMock();

        $this->commandGetList
            ->method('execute')
            ->with($searchCriteria)
            ->willReturn($this->searchResult);

        self::assertEquals($this->searchResult, $this->itemRepository->getList($searchCriteria));
    }
}
