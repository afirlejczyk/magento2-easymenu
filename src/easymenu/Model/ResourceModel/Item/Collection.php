<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model\ResourceModel\Item;

use AMF\EasyMenuApi\Api\Data\ItemInterface;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Resource Collection of Menu Items entities
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'item_id';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\AMF\EasyMenu\Model\Item::class, \AMF\EasyMenu\Model\ResourceModel\Item::class);
    }

    /**
     * Id field name getter
     *
     * @return string
     */
    public function getIdFieldName()
    {
        return $this->_idFieldName;
    }

    /**
     * @param int $storeId
     *
     * @return $this
     */
    public function addStoreFilter($storeId)
    {
        $this->addFieldToFilter(ItemInterface::STORE_ID, $storeId);

        return $this;
    }

    /**
     * @return $this
     */
    public function addActiveFilter()
    {
        $this->addFieldToFilter(ItemInterface::IS_ACTIVE, 1);

        return $this;
    }
}
