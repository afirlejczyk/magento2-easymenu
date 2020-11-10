<?php
/**
 * @package AMF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */
declare(strict_types=1);

namespace AMF\EasyMenu\Model\ResourceModel;

use AMF\EasyMenuApi\Api\Data\ItemInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Implementation of basic operations for Menu Item entity for specific db layer
 */
class Item extends AbstractDb
{
    const TABLE_NAME_MENU_ITEM = 'easymenu_item';

    /**
     * Get Children Ids
     *
     * @param AbstractModel $item
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getChildrenIds(AbstractModel $item): array
    {
        $connection = $this->getConnection();
        $select = $this->getConnection()->select()
            ->from($this->getMainTable(), [ItemInterface::ITEM_ID])
            ->where('item_id = ?', $item->getId());

        return $connection->fetchCol($select);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME_MENU_ITEM, 'item_id');
    }

    /**
     * @param AbstractModel $object
     *
     * @return AbstractDb
     * @throws LocalizedException
     */
    protected function _beforeSave(AbstractModel $object)
    {
        if ($object->getId() && (int) $object->getId() === (int) $object->getParentId()) {
            throw new LocalizedException(
                __('You cannot select yourself as parent. Please select different parent item.')
            );
        }

        return parent::_beforeSave($object);
    }

    /**
     * {@inheritdoc}
     */
    protected function _beforeDelete(AbstractModel $object)
    {
        parent::_beforeDelete($object);
        $this->updateChildren($object);
    }

    /**
     * Update Parent Id in Children
     *
     * @param AbstractModel $item
     *
     * @throws LocalizedException
     */
    private function updateChildren(AbstractModel $item): void
    {
        $connection = $this->getConnection();
        $where = $connection->prepareSqlCondition('item_id', ['in' => $this->getChildrenIds($item)]);

        $connection->update(
            $this->getMainTable(),
            ['parent_id' => $item->getParentId()],
            $where
        );
    }
}
