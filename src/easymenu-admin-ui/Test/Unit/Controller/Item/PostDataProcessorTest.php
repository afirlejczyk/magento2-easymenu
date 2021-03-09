<?php

namespace AMF\EasyMenuAdminUi\Test\Unit\Controller\Adminhtml\Item;

use AMF\EasyMenuAdminUi\Controller\Adminhtml\Item\PostDataProcessor;
use AMF\EasyMenuAdminUi\Ui\DataProvider\Item\ValueFieldLookup;
use PHPUnit\Framework\TestCase;

class PostDataProcessorTest extends TestCase
{
    /**
     * @var ValueFieldLookup
     */
    private $valueLookupMock;
    /**
     * @var PostDataProcessor
     */
    private $postDataProcessor;

    protected function setUp()
    {
        $this->valueLookupMock = $this->createMock(ValueFieldLookup::class);

        $this->postDataProcessor = new PostDataProcessor(
            $this->valueLookupMock
        );
    }

    public function testGetValueByType()
    {
        $value = '5';
        $valueFieldNameByType = 'cms_value';
        $type = 'cms';
        $itemData = [
            'type' => $type,
            'cms_value' => $value
        ];

        $this->valueLookupMock->method('getValueFieldNameByType')
            ->with($type)->willReturn($valueFieldNameByType);

        $resultData = $this->postDataProcessor->process($itemData);

        self::assertEquals(
            $value,
            $resultData['value']
        );
    }

    public function testGetEmptyValueByType()
    {
        $value = '5';
        $type = 'cms';
        $itemData = [
            'type' => $type,
            'cms_value' => $value
        ];

        $this->valueLookupMock->method('getValueFieldNameByType')
            ->with($type)->willReturn('');

        $resultData = $this->postDataProcessor->process($itemData);

        self::assertEquals(
            '',
            $resultData['value']
        );
    }

    public function testSetEmptyItemIdWhenKeyNotPresent()
    {
        $itemData = [
            'parent_id' => 0,
            'type' => 'cms'
        ];

        $resultData = $this->postDataProcessor->process($itemData);

        self::assertEquals(
            $resultData['item_id'],
            null
        );
    }

    public function testSetEmptyItemIdWhenIdIsEmptyString()
    {
        $itemData = [
            'parent_id' => 0,
            'type' => 'cms',
            'item_id' => '',
        ];

        $resultData = $this->postDataProcessor->process($itemData);

        self::assertEquals(
            $resultData['item_id'],
            null
        );
    }
}
