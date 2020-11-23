<?php

namespace AMF\EasyMenuAdminUi\Test\Unit\Ui\DataProvider\Item;

use AMF\EasyMenuAdminUi\Ui\DataProvider\Item\ValueFieldLookup;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for ValueFieldLookup class
 */
class ValueFieldLookupTest extends TestCase
{
    /** @var ValueFieldLookup */
    private $valueFieldLookup;

    protected function setUp()
    {
        $this->valueFieldLookup = new ValueFieldLookup();
    }

    /**
     * @param string $type
     * @param string $fieldName
     *
     * @dataProvider getDataProvider
     */
    public function testGetValueFieldNameByType(string $type, string $fieldName)
    {
        $this->assertEquals($fieldName, $this->valueFieldLookup->getValueFieldNameByType($type));
    }

    public function getDataProvider()
    {
        return [
            [ItemInterface::TYPE_CATEGORY, 'category_value'],
            [ItemInterface::TYPE_CMS_PAGE, 'cms_value'],
            [ItemInterface::TYPE_CUSTOM_LINK, 'external_value'],
            ['product', ''],
        ];
    }
}
