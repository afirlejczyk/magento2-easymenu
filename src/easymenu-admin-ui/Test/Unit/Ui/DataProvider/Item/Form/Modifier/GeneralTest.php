<?php

declare(strict_types=1);

use AMF\EasyMenuAdminUi\Ui\DataProvider\Item\Form\Modifier\General;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Store\Api\Data\StoreInterface;
use PHPUnit\Framework\TestCase;

class GeneralTest extends TestCase
{
    /** @var \PHPUnit\Framework\MockObject\MockObject */
    private $itemMock;
    /** @var \PHPUnit\Framework\MockObject\MockObject */
    private $dataPersistorMock;
    /** @var \PHPUnit\Framework\MockObject\MockObject */
    private $storeMock;
    /** @var General */
    private $generalModifier;

    protected function setUp()
    {
        $this->itemMock = $this->getMockBuilder(ItemInterface::class)->getMock();
        $this->storeMock = $this->getMockBuilder(StoreInterface::class)->getMock();

        $this->dataPersistorMock = $this->getMockBuilder(DataPersistorInterface::class)->getMock();

        $this->generalModifier = new General(
            $this->dataPersistorMock
        );
    }

    public function testModifyMetaWillNotChangeData()
    {
        $data = [
            'meta' => 1,
        ];

        self::assertEquals(
            $data,
            $this->generalModifier->modifyMeta($data)
        );
    }

    public function testResolverPersistentData()
    {
        $itemId = 22;

        $this->dataPersistorMock
            ->method('get')
            ->with('menu_item')
            ->willReturn([
                'item_id' => $itemId,
                'name' => 'Menu name'
            ]);

        $expectedData = [
            $itemId => [
                'item_id' => $itemId,
                'name' => 'Menu name'
            ]
        ];

        self::assertEquals(
            $expectedData,
            $this->generalModifier->modifyData([])
        );
    }

    public function testEmptyDataPersistor()
    {
        $this->dataPersistorMock
            ->method('get')
            ->with('menu_item')
            ->willReturn(null);

        self::assertEquals(
            [],
            $this->generalModifier->modifyData([])
        );
    }
}
