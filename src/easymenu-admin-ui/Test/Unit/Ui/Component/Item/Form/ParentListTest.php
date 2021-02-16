<?php

namespace AMF\EasyMenuAdminUi\Test\Unit\Ui\Component\Item\Form;

use AMF\EasyMenuAdminUi\Model\Locator\LocatorInterface;
use AMF\EasyMenuAdminUi\Ui\Component\Item\Form\ParentListOptions;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use AMF\EasyMenuApi\Api\Data\ItemSearchResultInterface;
use AMF\EasyMenuApi\Model\GetItemsByStoreIdInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ParentListTest extends TestCase
{
    /** @var MockObject */
    private $searchResult;

    /** @var ParentListOptions */
    private $parentListOptions;

    protected function setUp()
    {
        $this->searchResult = $this->getMockBuilder(ItemSearchResultInterface::class)->getMock();

        $locatorMock = $this->getMockBuilder(LocatorInterface::class)->getMock();
        $getItemsByStoreMock = $this->getMockBuilder(GetItemsByStoreIdInterface::class)->getMock();
        $getItemsByStoreMock->method('getAll')->willReturn($this->searchResult);

        $this->parentListOptions = new ParentListOptions(
            $locatorMock,
            $getItemsByStoreMock
        );
    }

    public function testGetEmptyArrayWhenNoItems()
    {
        $this->searchResult->method('getItems')->willReturn([]);

        self::assertEquals([], $this->parentListOptions->toOptionArray());
    }

    public function testGetOptionArray()
    {
        $itemId = 10;
        $parentId = 0;
        $itemName = 'Category 1';

        $itemIdSecond = 2;
        $parentIdSecond = 0;
        $itemNameSecond = 'Category 2';

        $expectedOptionsTree = [
            0 => [
                'value' => 0,
                'label' => __('-- None --'),
                'is_active' => 1,
                'optgroup' => [
                    [
                        'value' => $itemId,
                        'is_active' => 1,
                        'label' => $itemName,
                    ],
                    [
                        'value' => $itemIdSecond,
                        'is_active' => 1,
                        'label' => $itemNameSecond,
                    ]
                ],
            ],
        ];

        $itemMockFirst = $this->getMockBuilder(ItemInterface::class)->getMock();
        $itemMockFirst->method('getId')->willReturn($itemId);
        $itemMockFirst->method('getName')->willReturn($itemName);
        $itemMockFirst->method('getParentId')->willReturn($parentId);

        $itemMockSecond = $this->getMockBuilder(ItemInterface::class)->getMock();
        $itemMockSecond->method('getId')->willReturn($itemIdSecond);
        $itemMockSecond->method('getName')->willReturn($itemNameSecond);
        $itemMockSecond->method('getParentId')->willReturn($parentIdSecond);

        $this->searchResult->method('getItems')->willReturn([$itemMockFirst, $itemMockSecond]);

        self::assertEquals($expectedOptionsTree, $this->parentListOptions->toOptionArray());
    }
}
