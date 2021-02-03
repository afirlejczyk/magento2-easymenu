<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\Test\Unit\Model\Locator;

use AMF\EasyMenuAdminUi\Model\Locator\RegistryLocator;
use AMF\EasyMenuAdminUi\Registry\CurrentItem as ItemRegistry;
use AMF\EasyMenuAdminUi\Registry\CurrentStore as StoreRegistry;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Store\Api\Data\StoreInterface;
use PHPUnit\Framework\TestCase;

class RegistryLocatorTest extends TestCase
{
    /** @var \PHPUnit\Framework\MockObject\MockObject */
    private $itemMock;
    /** @var \PHPUnit\Framework\MockObject\MockObject */
    private $storeMock;
    /** @var ItemRegistry */
    private $itemRegistry;
    /** @var StoreRegistry|\PHPUnit\Framework\MockObject\MockObject  */
    private $storeRegistry;
    /** @var RegistryLocator */
    private $registryLocator;

    protected function setUp()
    {
        $this->itemMock = $this->getMockBuilder(ItemInterface::class)->getMock();
        $this->storeMock = $this->getMockBuilder(StoreInterface::class)->getMock();

        $this->storeRegistry = $this->createPartialMock(StoreRegistry::class, ['set', 'get']);
        $this->itemRegistry = $this->createPartialMock(ItemRegistry::class, ['set', 'get']);

        $this->registryLocator = new RegistryLocator(
            $this->storeRegistry,
            $this->itemRegistry
        );
    }

    public function testGetMenuItem()
    {
        $this->itemRegistry->method('get')->willReturn(
            $this->itemMock
        );

        self::assertEquals(
            $this->itemMock,
            $this->registryLocator->getMenuItem()
        );
    }

    public function testGetMenuItemWillThrowNotFoundException()
    {
        self::expectExceptionObject(
            new NotFoundException(__('Menu Item was not registered'))
        );

        $this->registryLocator->getMenuItem();
    }

    public function testGetStore()
    {
        $this->storeRegistry->method('get')->willReturn(
            $this->storeMock
        );

        self::assertEquals(
            $this->storeMock,
            $this->registryLocator->getStore()
        );
    }

    public function testGetStoreWillThrowNotFoundException()
    {
        self::expectExceptionObject(
            new NotFoundException(__('Store was not registered'))
        );

        $this->registryLocator->getStore();
    }
}
