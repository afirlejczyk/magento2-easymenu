<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\Test\Unit\Controller\Adminhtml\Item;

use AMF\EasyMenuAdminUi\Controller\Adminhtml\Item\Builder;
use AMF\EasyMenuAdminUi\Registry\CurrentItem as ItemRegistry;
use AMF\EasyMenuAdminUi\Registry\CurrentStore as StoreRegistry;
use AMF\EasyMenuApi\Api\ItemRepositoryInterface;
use AMF\EasyMenuApi\Api\Data\ItemInterfaceFactory;
use Magento\Framework\App\Request\Http;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class BuilderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var Builder
     */
    private $builder;

    /**
     * @var ItemRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $itemRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $itemMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $requestMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $itemRegistryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $storeRegistryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $itemFactoryMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $storeManagerMock;

    /**
     * @var \Magento\Store\Model\Store|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $storeMock;

    /**
     *
     */
    protected function setUp()
    {
        $this->objectManager = new ObjectManager($this);
        $this->requestMock = $this->createMock(Http::class);

        $this->itemMock = $this->getMockBuilder(\AMF\EasyMenuApi\Api\Data\ItemInterface::class)->getMock();


        $this->itemFactoryMock = $this->createPartialMock(ItemInterfaceFactory::class, ['create']);

        $this->itemRegistryMock = $this->createMock(ItemRegistry::class);
        $this->storeRegistryMock = $this->createMock(StoreRegistry::class);

        $this->storeManagerMock = $this->getMockBuilder(StoreManagerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMockForAbstractClass();

        $this->storeMock = $this->getMockBuilder(\Magento\Store\Model\Store::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->itemRepositoryMock =  $this->getMockBuilder(ItemRepositoryInterface::class)
            ->setMethods(['get'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->builder = $this->objectManager->getObject(
            Builder::class,
            [
                'itemFactory' => $this->itemFactoryMock,
                'itemRegistry' => $this->itemRegistryMock,
                'storeRegistry' => $this->storeRegistryMock,
                'itemRepository' => $this->itemRepositoryMock,
                'storeManager' => $this->storeManagerMock,
            ]
        );
    }

    public function testBuildWhenItemExistingAndPossibleToLoad()
    {
        $itemId = 2;
        $itemParentId = 1;

        $valueMap = [
            ['item_id', null, $itemId],
            ['parent_id', null, $itemParentId],
        ];
        $this->requestMock->expects($this->any())
            ->method('getParam')
            ->willReturnMap($valueMap);

        $this->itemRepositoryMock->expects($this->once())
            ->method('get')
            ->with($itemId)
            ->willReturn($this->itemMock);

        $this->itemMock->expects($this->any())
            ->method('setParentId')
            ->with($itemParentId);

        $this->storeManagerMock->expects($this->once())
            ->method('getStore')
            ->willReturn($this->storeMock);

        $this->itemRegistryMock->expects($this->once())
            ->method('set')
            ->willReturn($this->itemMock);

        $this->storeRegistryMock->expects($this->once())
            ->method('set')
            ->willReturn($this->storeMock);

        $this->assertEquals($this->itemMock, $this->builder->build($this->requestMock));
    }

    public function testBuildWhenImpossibleLoadItem()
    {
        $itemId = 3;
        $itemStoreId = 1;
        $itemParentId = 1;

        $valueMap = [
            ['item_id', null, $itemId],
            ['parent_id', null, $itemParentId],
            ['store', $itemStoreId, $itemStoreId],
        ];
        $this->requestMock->expects($this->any())
            ->method('getParam')
            ->willReturnMap($valueMap);

        $this->itemRepositoryMock->expects($this->once())
            ->method('get')
            ->with($itemId)
            ->willThrowException(new NoSuchEntityException());

        $this->itemFactoryMock->expects($this->once())
            ->method('create')
            ->will($this->returnValue($this->itemMock));
        $this->itemMock->expects($this->any())
            ->method('setParentId')
            ->with($itemParentId);

        $this->storeMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($itemStoreId);
        $this->storeManagerMock->expects($this->once())
            ->method('getDefaultStoreView')
            ->willReturn($this->storeMock);

        $this->storeManagerMock->expects($this->atLeastOnce())
            ->method('getStore')
            ->willReturn($this->storeMock);

        $this->itemRegistryMock->expects($this->once())
            ->method('set')
            ->willReturn($this->itemMock);
        $this->storeRegistryMock->expects($this->once())
            ->method('set')
            ->willReturn($this->storeMock);

        $this->assertEquals($this->itemMock, $this->builder->build($this->requestMock));
    }
}
