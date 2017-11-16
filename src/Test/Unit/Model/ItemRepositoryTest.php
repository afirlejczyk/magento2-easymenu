<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Test\Unit\Model;

use AF\EasyMenu\Api\Data\ItemInterface;
use AF\EasyMenu\Model\ItemRepository;
use AF\EasyMenu\Model\Item\Command\DeleteInterface;
use AF\EasyMenu\Model\Item\Command\GetInterface;
use AF\EasyMenu\Model\Item\Command\SaveInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use PHPUnit\Framework\TestCase;

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
     * @var DeleteInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $commandDelete;

    /**
     * @var ItemRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $itemRepository;

    /**
     * @var ItemInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $item;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->commandSave = $this->getMockBuilder(SaveInterface::class)->getMock();
        $this->commandGet = $this->getMockBuilder(GetInterface::class)->getMock();
        $this->commandDelete = $this->getMockBuilder(DeleteInterface::class)->getMock();
        $this->item = $this->getMockBuilder(ItemInterface::class)->getMock();

        $this->itemRepository = (new ObjectManager($this))->getObject(
            ItemRepository::class,
            [
                'commandDelete' => $this->commandDelete,
                'commandGet' => $this->commandGet,
                'commandSave' => $this->commandSave,
            ]
        );
    }

    /**
     * @return void
     */
    public function testGet()
    {
        $itemId = 3;

        $this->commandGet
            ->expects($this->once())
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
        $sourceId = 3;

        $this->commandGet
            ->expects($this->once())
            ->method('execute')
            ->with($sourceId)
            ->willThrowException(new NoSuchEntityException(__('Some error')));

        $this->itemRepository->get($sourceId);
    }

    /**
     * @return void
     */
    public function testSave()
    {
        $itemId = 3;

        $this->commandSave
            ->expects($this->once())
            ->method('execute')
            ->with($this->item)
            ->willReturn($itemId);

        self::assertEquals($itemId, $this->itemRepository->save($this->item));
    }

    /**
     * @expectedException \Magento\Framework\Exception\CouldNotSaveException
     * @expectedExceptionMessage Some error
     */
    public function testSaveWithCouldNotSaveException()
    {
        $this->commandSave
            ->expects($this->once())
            ->method('execute')
            ->with($this->item)
            ->willThrowException(new CouldNotSaveException(__('Some error')));

        $this->itemRepository->save($this->item);
    }

    /**
     * @return void
     */
    public function testDelete()
    {
        $this->commandDelete
            ->expects($this->once())
            ->method('execute')
            ->with($this->item);

        self::assertNull($this->itemRepository->delete($this->item));
    }

    /**
     * @expectedException \Magento\Framework\Exception\CouldNotDeleteException
     * @expectedExceptionMessage Some error
     */
    public function testDeleteWithCouldNotDeleteException()
    {
        $this->commandDelete
            ->expects($this->once())
            ->method('execute')
            ->with($this->item)
            ->willThrowException(new CouldNotDeleteException(__('Some error')));

        $this->itemRepository->delete($this->item);
    }
}
