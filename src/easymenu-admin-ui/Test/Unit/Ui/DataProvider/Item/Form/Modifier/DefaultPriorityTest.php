<?php

declare(strict_types=1);

use AMF\EasyMenuAdminUi\Model\Locator\LocatorInterface;
use AMF\EasyMenuAdminUi\Ui\DataProvider\Item\Form\Modifier\DefaultPriority;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use AMF\EasyMenuApi\Model\GetMaxPriorityInterface;
use Magento\Store\Api\Data\StoreInterface;
use PHPUnit\Framework\TestCase;

class DefaultPriorityTest extends TestCase
{
    /** @var \PHPUnit\Framework\MockObject\MockObject */
    private $locatorMock;
    /** @var \PHPUnit\Framework\MockObject\MockObject */
    private $itemMock;
    /** @var DefaultPriority */
    private $defaultPriority;
    /** @var \PHPUnit\Framework\MockObject\MockObject */
    private $getMaxPriorityMock;
    /** @var \PHPUnit\Framework\MockObject\MockObject */
    private $storeMock;
    protected function setUp()
    {
        $this->itemMock = $this->getMockBuilder(ItemInterface::class)->getMock();
        $this->storeMock = $this->getMockBuilder(StoreInterface::class)->getMock();

        $this->locatorMock = $this->getMockBuilder(LocatorInterface::class)->getMock();
        $this->locatorMock->method('getMenuItem')->willReturn($this->itemMock);
        $this->locatorMock->method('getStore')->willReturn($this->storeMock);

        $this->getMaxPriorityMock = $this->getMockBuilder(GetMaxPriorityInterface::class)->getMock();

        $this->defaultPriority = new DefaultPriority(
            $this->locatorMock,
            $this->getMaxPriorityMock
        );
    }

    public function testModifyDataWillNotChangeData()
    {
        $data = [
            'meta' => 1,
        ];

        self::assertEquals(
            $data,
            $this->defaultPriority->modifyData($data)
        );
    }

    public function testCustomizePriorityField()
    {
        $storeId = 1;
        $parentId = 10;
        $this->itemMock->method('getParentId')->willReturn($parentId);
        $this->storeMock->method('getId')->willReturn($storeId);

        $maxPriority = 2;
        $this->getMaxPriorityMock->method('execute')->with($storeId, $parentId)->willReturn($maxPriority);

        $expectedMeta = [
            'general' => [
                'children' => [
                    'priority' => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'default' => $maxPriority + 1
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        self::assertEquals(
            $expectedMeta,
            $this->defaultPriority->modifyMeta([])
        );
    }
}
