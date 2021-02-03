<?php

namespace AMF\EasyMenuAdminUi\Test\Unit\Registry;

use AMF\EasyMenuAdminUi\Registry\CurrentItem;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use PHPUnit\Framework\TestCase;

class CurrentItemTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $itemMock;

    /**
     * @var CurrentItem
     */
    private $currentItemRegistry;

    protected function setUp()
    {
        $this->itemMock = $this->getMockBuilder(ItemInterface::class)->getMock();
        $this->currentItemRegistry = new CurrentItem();
    }

    public function testGetStoreFromRegistry()
    {
        $this->currentItemRegistry->set($this->itemMock);

        self::assertEquals(
            $this->itemMock,
            $this->currentItemRegistry->get()
        );
    }
}
