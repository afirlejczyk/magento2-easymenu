<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Controller\Adminhtml\Item;

use AF\EasyMenu\Ui\DataProvider\Item\ValueLookup;
use AF\EasyMenu\Model\Item as MenuItem;

/**
 * Class PostDataProcessor
 */
class PostDataProcessor
{

    /**
     * @var ValueLookup
     */
    private $valueLookup;

    /**
     * PostDataProcessor constructor.
     *
     * @param ValueLookup $valueLookup
     */
    public function __construct(ValueLookup $valueLookup)
    {
        $this->valueLookup = $valueLookup;
    }

    /**
     * @param array $data
     *
     * @return array|mixed
     */
    public function process(array $data)
    {
        $data['value'] = $this->getValueByType($data);

        if (empty($data[MenuItem::ITEM_ID])) {
            $data[MenuItem::ITEM_ID] = null;
        }

        return $data;
    }

    /**
     * @param array $data
     *
     * @return string
     */
    private function getValueByType(array $data)
    {
        $type = (int) $data['type'];

        $valueLookupFieldName = $this->valueLookup->getValueFieldNameByType($type);

        if ($valueLookupFieldName) {
            return $data[$valueLookupFieldName];
        }

        return '';
    }
}
