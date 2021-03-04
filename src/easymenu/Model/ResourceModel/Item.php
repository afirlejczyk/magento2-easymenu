<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model\ResourceModel;

use AMF\EasyMenu\Exception\WrongParentException;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Implementation of basic operations for Menu Item entity for specific db layer
 */
class Item extends AbstractDb
{
    public const TABLE_NAME_MENU_ITEM = 'easymenu_item';

    /**
     * Get Children Ids
     *
     * @return array<int>
     *
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
     */
    protected function _construct(): void
    {
        $this->_init(self::TABLE_NAME_MENU_ITEM, 'item_id');
    }

    /**
     * @param AbstractModel|\AMF\EasyMenu\Model\Item $object
     *
     * @return AbstractDb
     *
     * @throws WrongParentException
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    protected function _beforeSave(AbstractModel $object)
    {
        if ($object->getId() && (int) $object->getId() === (int) $object->getParentId()) {
            throw new WrongParentException();
        }

        return parent::_beforeSave($object);
    }

    /**
     * {@inheritdoc}
     *
     * @return AbstractModel
     *
     * @throws LocalizedException
     */
    protected function _beforeDelete(AbstractModel $object)
    {
        parent::_beforeDelete($object);
        $this->updateChildren($object);

        return $this;
    }

    /**
     * Update Parent Id in Children
     *
     * @param AbstractModel|\AMF\EasyMenu\Model\Item $item
     *
     * @throws LocalizedException
     */
    private function updateChildren(AbstractModel $item): void
    {
        $connection = $this->getConnection();

        $connection->update(
            $this->getMainTable(),
            ['parent_id' => $item->getParentId()],
            $this->buildWhereCondition($item)
        );
    }

    private function buildWhereCondition(AbstractModel $item): string
    {
        $connection = $this->getConnection();

        return $connection->prepareSqlCondition(
            'item_id',
            ['in' => $this->getChildrenIds($item)]
        );
    }
}
