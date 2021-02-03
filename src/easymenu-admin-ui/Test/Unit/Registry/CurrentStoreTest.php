<?php

namespace AMF\EasyMenuAdminUi\Test\Unit\Registry;

use AMF\EasyMenuAdminUi\Registry\CurrentStore;
use Magento\Store\Api\Data\StoreInterface;
use PHPUnit\Framework\TestCase;

class CurrentStoreTest extends TestCase
{
    /** @var \PHPUnit\Framework\MockObject\MockObject */
    private $storeMock;
    /** @var CurrentStore */
    private $currentStoreRegistry;

    protected function setUp()
    {
        $this->storeMock = $this->getMockBuilder(StoreInterface::class)
            ->getMock();
        $this->currentStoreRegistry = new CurrentStore();
    }

    public function testGetStoreFromRegistry()
    {
        $this->currentStoreRegistry->set($this->storeMock);

        self::assertEquals(
            $this->storeMock,
            $this->currentStoreRegistry->get()
        );
    }
}
