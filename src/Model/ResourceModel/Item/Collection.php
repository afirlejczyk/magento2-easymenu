<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Model\ResourceModel\Item;

use AF\EasyMenu\Api\Data\ItemInterface;
use AF\EasyMenu\Model\Item as MenuItem;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Item Collection
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'item_id';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('AF\EasyMenu\Model\Item', 'AF\EasyMenu\Model\ResourceModel\Item');
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

    /**
     * @return $this
     */
    public function addCmsPageFilter()
    {
        $this->addFieldToFilter(ItemInterface::TYPE, MenuItem::TYPE_CMS_PAGE);

        return $this;
    }

    /**
     * @return $this
     */
    public function addCatalogFilter()
    {
        $this->addFieldToFilter(ItemInterface::TYPE, MenuItem::TYPE_CATEGORY);

        return $this;
    }
}
