<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Ui\DataProvider\Item;

use AF\EasyMenu\Model\Item;

/**
 * Class ValueLookup
 */
class ValueLookup
{

    /**
     * @var array
     */
    private $valueLookupTable = [
        Item::TYPE_CUSTOM_LINK => 'external_value',
        Item::TYPE_CMS_PAGE => 'cms_value',
        Item::TYPE_CATEGORY => 'category_value',
    ];

    /**
     * @param int $type
     *
     * @return string
     */
    public function getValueFieldNameByType($type)
    {
        if (isset($this->valueLookupTable[$type])) {
            return $this->valueLookupTable[$type];
        }

        return '';
    }
}
