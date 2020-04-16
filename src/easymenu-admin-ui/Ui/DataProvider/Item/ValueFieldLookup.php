<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\Ui\DataProvider\Item;

use AMF\EasyMenuApi\Api\Data\ItemInterface;

/**
 * Class ValueLookup
 */
class ValueFieldLookup
{
    /**
     * @var array
     */
    private $valueLookupTable = [
        ItemInterface::TYPE_CUSTOM_LINK => 'external_value',
        ItemInterface::TYPE_CMS_PAGE => 'cms_value',
        ItemInterface::TYPE_CATEGORY => 'category_value',
    ];

    /**
     * @param string $type
     *
     * @return string
     */
    public function getValueFieldNameByType(string $type): string
    {
        return $this->valueLookupTable[$type] ?? '';
    }
}
