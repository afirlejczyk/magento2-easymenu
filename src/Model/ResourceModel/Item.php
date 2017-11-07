<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Model\ResourceModel;

use AF\EasyMenu\Model\Item as ModelItem;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Item
 */
class Item extends AbstractDb
{
    const TABLE_NAME = 'easymenu_item';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, 'item_id');
    }

    /**
     * @param ModelItem $menuItem
     * @param ModelItem $newParent
     * @param null|int $afterMenuItemId
     *
     * @return Item
     */
    public function changeParent(
        ModelItem $menuItem,
        ModelItem $newParent = null,
        $afterMenuItemId = null
    ) {
        $table = $this->getMainTable();
        $connection = $this->getConnection();

        /**
         * Update moved page data
         */
        if (null === $newParent) {
            $newParent = 0;
        } else {
            $newParent = $newParent->getId();
        }

        $priority = $this->processPriority($menuItem, $newParent, $afterMenuItemId);

        /**
         * Update moved item data
         */
        $data = [
            ModelItem::PARENT_ID => $newParent,
            ModelItem::PRIORITY => $priority,
        ];
        $connection->update(
            $table,
            $data,
            ['item_id = ?' => $menuItem->getId()]
        );

        $menuItem->addData($data);

        return $this;
    }

    /**
     * @param ModelItem $menuItem
     * @param int $newParentId
     * @param int|null $afterMenuItemId
     *
     * @return int
     */
    protected function processPriority(ModelItem $menuItem, $newParentId, $afterMenuItemId)
    {
        $table = $this->getMainTable();
        $connection = $this->getConnection();
        $priorityField = $connection->quoteIdentifier(ModelItem::PRIORITY);

        $bind = [ModelItem::PRIORITY => new \Zend_Db_Expr($priorityField . ' - 1')];
        $where = [
            'parent_id = ?' => $menuItem->getParentId(),
            $priorityField . ' > ?' => $menuItem->getPriority(),
        ];

        $connection->update($table, $bind, $where);

        /**
         * Prepare priority value
         */
        if ($afterMenuItemId) {
            $select = $connection->select()
                ->from($table, ModelItem::PRIORITY)
                ->where('item_id = :item_id');
            $priority = (int) $connection->fetchOne($select, ['item_id' => $afterMenuItemId]);
            $priority++;
        } else {
            $priority = 1;
        }

        $bind = [ModelItem::PRIORITY => new \Zend_Db_Expr($priorityField . ' + 1')];
        $where = [
            'parent_id = ?' => $newParentId,
            $priorityField . ' >= ?' => $priority,
        ];
        $connection->update($table, $bind, $where);

        return $priority;
    }

    /**
     * @param int $storeId
     * @param int $parentId
     *
     * @return int
     */
    public function getLastPriorityBaseOnParentId($storeId, $parentId)
    {
        $table = $this->getMainTable();
        $connection = $this->getConnection();

        $select = $connection->select()->from($table, []);

        if ($parentId) {
            $select->where('parent_id = ?', $parentId);
            $select->columns(['max' => new \Zend_Db_Expr('MAX(priority) + 1')]);
        } else {
            $select->where('store_id = ?', $storeId);
            $select->columns(['count' => new \Zend_Db_Expr('count(priority) + 1')]);
        }

        $result = $connection->fetchOne($select);

        if (null === $result) {
            return 1;
        }

        return (int) $result;
    }
}
