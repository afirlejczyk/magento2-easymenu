<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\Controller\Adminhtml\Item;

use AMF\EasyMenuAdminUi\Ui\DataProvider\Item\ValueFieldLookup;
use AMF\EasyMenuApi\Api\Data\ItemInterface;

/**
 * Process item post data
 */
class PostDataProcessor
{
    /**
     * @var ValueFieldLookup
     */
    private $valueLookup;

    /**
     * PostDataProcessor constructor.
     *
     * @param ValueFieldLookup $valueLookup
     */
    public function __construct(ValueFieldLookup $valueLookup)
    {
        $this->valueLookup = $valueLookup;
    }

    /**
     * Process Item post data
     *
     * @param array $data
     *
     * @return array
     */
    public function process(array $data): array
    {
        $data['value'] = $this->getValueByType($data);

        if (empty($data[ItemInterface::ITEM_ID])) {
            $data[ItemInterface::ITEM_ID] = null;
        }

        return $data;
    }

    /**
     * Retrieve Value by Item Type
     *
     * @param array $data
     *
     * @return string
     */
    private function getValueByType(array $data): string
    {
        $type = $data['type'];
        $valueLookupFieldName = $this->valueLookup->getValueFieldNameByType($type);

        if ($valueLookupFieldName) {
            return $data[$valueLookupFieldName];
        }

        return '';
    }
}
