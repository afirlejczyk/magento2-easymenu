<?php

declare(strict_types=1);

use AMF\EasyMenuAdminUi\Model\Locator\LocatorInterface;
use AMF\EasyMenuAdminUi\Ui\DataProvider\Item\Form\ItemDataProvider;
use AMF\EasyMenuAdminUi\Ui\DataProvider\Item\Form\Modifier\General;
use AMF\EasyMenuAdminUi\Ui\DataProvider\Item\ValueFieldLookup;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use Magento\Framework\Api\Filter;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use PHPUnit\Framework\TestCase;

class ItemDataProviderTest extends TestCase
{
    /** @var \PHPUnit\Framework\MockObject\MockObject */
    private $locatorMock;
    /** @var \PHPUnit\Framework\MockObject\MockObject */
    private $itemMock;
    /** @var \PHPUnit\Framework\MockObject\MockObject */
    private $valueLookupMock;
    /** @var \PHPUnit\Framework\MockObject\MockObject */
    private $dataPersistorMock;
    /** @var \PHPUnit\Framework\MockObject\MockObject */
    private $storeMock;
    /** @var \PHPUnit\Framework\MockObject\MockObject */
    private $poolMock;

    private $dataProvider;

    protected function setUp()
    {
        $this->itemMock = $this->getMockBuilder(ItemInterface::class)->getMock();
        $this->storeMock = $this->getMockBuilder(StoreInterface::class)->getMock();

        $this->locatorMock = $this->getMockBuilder(LocatorInterface::class)->getMock();

        $this->valueLookupMock = $this->getMockBuilder(ValueFieldLookup::class)->getMock();
        $this->poolMock = $this->getMockBuilder(PoolInterface::class)->getMock();

        $this->dataProvider = new ItemDataProvider(
            $this->poolMock,
            $this->locatorMock,
            $this->valueLookupMock,
            'name',
            'id',
            'id',
            [],
            []
        );
    }

    public function testAddFilterWillReturnNull()
    {
        $filterMock = $this->getMockBuilder(Filter::class)->getMock();

        self::assertNull(
            $this->dataProvider->addFilter($filterMock)
        );
    }

    public function testModifierWillChangeMeta()
    {
        $expectedMeta = ['meta' => 2];
        $modifierMock = $this->getMockBuilder(ModifierInterface::class)->getMock();
        $this->poolMock->method('getModifiersInstances')->willReturn([$modifierMock]);

        $modifierMock->method('modifyMeta')->willReturn($expectedMeta);

        self::assertEquals(
            $expectedMeta,
            $this->dataProvider->getMeta()
        );
    }

    public function testGetMetaWillNotChangeMeta()
    {
        $this->poolMock->method('getModifiersInstances')->willReturn([]);

        $dataProvider = new ItemDataProvider(
            $this->poolMock,
            $this->locatorMock,
            $this->valueLookupMock,
            'name',
            'id',
            'id',
            ['meta' => 1],
            []
        );

        self::assertEquals(
            ['meta' => 1],
            $dataProvider->getMeta()
        );
    }

    public function testGetDataModifiers()
    {
        $itemId = 11;
        $this->itemMock->method('getId')->willReturn($itemId);

        $this->locatorMock->method('getMenuItem')->willReturn($this->itemMock);


        $modifierMock = $this->getMockBuilder(ModifierInterface::class)->getMock();
        $modifierMock->method('modifyData')->willReturn([10 => ['new' => 'sth']]);

        $this->poolMock->method('getModifiersInstances')->willReturn([$modifierMock]);

        self::assertEquals(
            [10 => ['new' => 'sth']],
            $this->dataProvider->getData()
        );
    }

    public function testGetData()
    {
        $this->poolMock->method('getModifiersInstances')->willReturn([]);

        $itemId = 11;
        $value = 'cms-id';
        $typeId = 10;
        $name = 'Category Item';
        $this->itemMock->method('getTypeId')->willReturn($typeId);
        $this->itemMock->method('getId')->willReturn($itemId);
        $this->itemMock->method('getValue')->willReturn($value);
        $this->itemMock->method('getName')->willReturn($name);
        $this->itemMock->method('isActive')->willReturn(true);

        $this->locatorMock->method('getMenuItem')->willReturn($this->itemMock);
        $this->locatorMock->method('getStore')->willReturn($this->storeMock);

        $this->valueLookupMock
            ->method('getValueFieldNameByType')
            ->with($typeId)
            ->willReturn('cms');

        $result = $this->dataProvider->getData();
        self::assertArrayHasKey($itemId, $result);

        $expectedItemArray = [
            'item_id' => $itemId,
            'type' => $typeId,
            'cms' => $value,
            'parent_id' => null,
            'name' => $name,
            'store_id' => null,
            'priority' => null,
            'is_active' => 1,
        ];

        self::assertEquals($expectedItemArray, $result[$itemId]);
        self::assertTrue('1' === $result[$itemId]['is_active']);
    }
}
