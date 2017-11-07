<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Model;

use AF\EasyMenu\Model\ResourceModel\Item\Collection as ItemCollection;
use AF\EasyMenu\Model\ResourceModel\Item\CollectionFactory as ItemCollectionFactory;

/**
 * Class ItemManagement
 */
class ItemManagement implements ItemManagementInterface
{
    /**
     * @var ItemCollectionFactory
     */
    private $itemCollectionFactory;

    /**
     * @var array
     */
    private $cacheItemInstance = [];

    /**
     * @var array
     */
    private $cacheActiveItemInstance = [];

    /**
     * ItemManagement constructor.
     *
     * @param ItemCollectionFactory $itemCollection
     */
    public function __construct(ItemCollectionFactory $itemCollection)
    {
        $this->itemCollectionFactory = $itemCollection;
    }

    /**
     * @param int $storeId
     *
     * @return ItemCollection
     */
    public function getActiveMenuItems($storeId)
    {
        if (!isset($this->cacheActiveItemInstance[$storeId])) {
            /** @var ItemCollection $collection */
            $collection = $this->itemCollectionFactory->create();
            $collection
                ->addStoreFilter($storeId)
                ->addActiveFilter()
                ->setOrder(Item::PARENT_ID, 'ASC')
                ->setOrder(Item::PRIORITY, 'ASC');

            $this->cacheActiveItemInstance[$storeId] = $collection;
        }

        return $this->cacheActiveItemInstance[$storeId];
    }

    /**
     * @param int $storeId
     *
     * @return ItemCollection
     */
    public function getAllMenuItems($storeId)
    {
        if (!isset($this->cacheItemInstance[$storeId])) {
            /** @var ItemCollection $collection */
            $collection = $this->itemCollectionFactory->create();
            $collection
                ->addStoreFilter($storeId)
                ->setOrder(Item::PARENT_ID, 'ASC')
                ->setOrder(Item::PRIORITY, 'ASC');

            $this->cacheItemInstance[$storeId] = $collection;
        }

        return $this->cacheItemInstance[$storeId];
    }

    /**
     * @param int $parentId
     * @param bool $object
     *
     * @return ItemCollection|array
     */
    public function getChildren($parentId, $object = false)
    {
        if (!$object) {
            $childrenMenuItems = $this->prepareChildrenCollection($parentId)->getData();
        } else {
            $childrenMenuItems = $this->prepareChildrenCollection($parentId);
        }

        return $childrenMenuItems;
    }

    /**
     * @param int $parentId
     *
     * @return ItemCollection
     */
    private function prepareChildrenCollection($parentId)
    {
        /** @var ItemCollection $collection */
        $collection = $this->itemCollectionFactory->create();
        $collection->setOrder(Item::PRIORITY, 'ASC');
        $collection->addFieldToFilter(Item::PARENT_ID, $parentId);

        return $collection;
    }
}
