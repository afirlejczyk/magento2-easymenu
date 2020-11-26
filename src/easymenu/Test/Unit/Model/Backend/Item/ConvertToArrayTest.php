<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Test\Unit\Model;

use AMF\EasyMenu\Model\Backend\Item\ConvertToArray;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ConvertToArrayTest extends TestCase
{
    /** @var ItemInterface|\PHPUnit\Framework\MockObject\MockObject */
    private $item;

    /** @var ConvertToArray */
    private $convertToArray;

    protected function setUp()
    {
        $this->item = $this->getMockBuilder(ItemInterface::class)->getMock();
        $this->convertToArray = new ConvertToArray();
    }

    /**
     * @dataProvider getItemDataProvider
     *
     * @param array $itemData
     * @param bool $isActive
     * @param string $cls
     */
    public function testConvertingItemToArray(array $itemData, bool $isActive, string $cls)
    {
        [$name, $itemId, $parentId, $value, $priority] = $itemData;

        $this->item->method('getId')->willReturn($itemId);
        $this->item->method('getName')->willReturn($name);
        $this->item->method('getParentId')->willReturn($parentId);
        $this->item->method('getValue')->willReturn($value);
        $this->item->method('getPriority')->willReturn($priority);
        $this->item->method('isActive')->willReturn($isActive);

        self::assertEquals(
            [
                'text' => $name,
                'id' => $itemId,
                'parent_id' => $parentId,
                'value' => $value,
                'priority' => $priority,
                'cls' => $cls
            ], $this->convertToArray->execute($this->item)
        );
    }

    /**
     * @return array
     */
    public function getItemDataProvider(): array
    {
        return [
            [
                [
                    'Category 1',
                    1,
                    0,
                    '3',
                    1
                ],
                false,
                'folder no-active-category'
            ],
            [
                [
                    'Category 1',
                    1,
                    0,
                    '3',
                    1
                ],
                true,
                'folder active-category'
            ]
        ];
    }
}
