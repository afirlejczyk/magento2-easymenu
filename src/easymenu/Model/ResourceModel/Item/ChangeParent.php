<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model\ResourceModel\Item;

use AMF\EasyMenu\Model\ResourceModel\Item;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use Magento\Framework\App\ResourceConnection;

/**
 * Class ChangeParent is responsible for changing parent
 */
class ChangeParent
{
    /**
     * @var ResourceConnection
     */
    private $resource;

    /**
     * ChangeParent constructor.
     *
     * @param ResourceConnection $resource
     */
    public function __construct(ResourceConnection $resource)
    {
        $this->resource = $resource;
    }

    /**
     * Change Item Parent
     *
     * @param ItemInterface $menuItem
     * @param ItemInterface|null $newParent
     * @param int|null $afterMenuItemId
     *
     * @throws \Exception
     */
    public function execute(
        ItemInterface $menuItem,
        ?ItemInterface $newParent,
        ?int $afterMenuItemId
    ) {
        $connection = $this->resource->getConnection();
        $connection->beginTransaction();

        try {
            $newParent = $newParent instanceof ItemInterface ? (int) $newParent->getId() : 0;

            $priority = $this->processPriority($menuItem, $newParent, (int) $afterMenuItemId);
            $this->updateMenuItem($menuItem, $newParent, $priority);
            $connection->commit();
        } catch (\Exception $exception) {
            $connection->rollBack();
            throw $exception;
        }
    }

    /**
     * Update parent
     *
     * @param ItemInterface $menuItem
     * @param int $parentId
     * @param int $priority
     */
    private function updateMenuItem(ItemInterface $menuItem, int $parentId, int $priority): void
    {
        $connection = $this->resource->getConnection();

        $connection->update(
            $this->getMenuItemTable(),
            [
                ItemInterface::PARENT_ID => $parentId,
                ItemInterface::PRIORITY => $priority,
            ],
            ['item_id = ?' => $menuItem->getId()]
        );
        $menuItem->setParentId($parentId);
        $menuItem->setPriority($priority);
    }

    /**
     * Process priority
     *
     * @param ItemInterface $menuItem
     * @param int $newParentId
     * @param int $afterMenuItemId
     *
     * @return int
     */
    private function processPriority(ItemInterface $menuItem, int $newParentId, int $afterMenuItemId): int
    {
        $this->updatePriorityForPreviousParent($menuItem);

        $priority = $this->calculatePriority($afterMenuItemId);
        $this->updatePriorityForNewParent($newParentId, $priority);

        return $priority;
    }

    /**
     * Update priority for items with previous parent
     *
     * @param ItemInterface $menuItem
     *
     * @return void
     */
    private function updatePriorityForPreviousParent(ItemInterface $menuItem): void
    {
        $connection = $this->resource->getConnection();
        $priorityField = $connection->quoteIdentifier(ItemInterface::PRIORITY);

        $bind = [ItemInterface::PRIORITY => new \Zend_Db_Expr($priorityField . ' - 1')];
        $where = [
            'parent_id = ?' => $menuItem->getParentId(),
            $priorityField . ' > ?' => $menuItem->getPriority(),
        ];

        $connection->update($this->getMenuItemTable(), $bind, $where);
    }

    /**
     * Update priority for items with new parent
     *
     * @param int $newParentId
     * @param int $priority
     *
     * @return void
     */
    private function updatePriorityForNewParent(int $newParentId, int $priority): void
    {
        $connection = $this->resource->getConnection();
        $priorityField = $connection->quoteIdentifier(ItemInterface::PRIORITY);

        $bind = [ItemInterface::PRIORITY => new \Zend_Db_Expr($priorityField . ' + 1')];
        $where = [
            'parent_id = ?' => $newParentId,
            $priorityField . ' >= ?' => $priority,
        ];

        $connection->update($this->getMenuItemTable(), $bind, $where);
    }

    /**
     * Calculate priority value
     *
     * @param int $afterMenuItemId
     *
     * @return int
     */
    private function calculatePriority(int $afterMenuItemId): int
    {
        $connection = $this->resource->getConnection();

        $select = $connection->select()
            ->from($this->getMenuItemTable(), ItemInterface::PRIORITY)
            ->where('item_id = :item_id');

        $priority = (int) $connection->fetchOne($select, ['item_id' => $afterMenuItemId]);

        return ++$priority;
    }

    /**
     * Retrieve menu item table name
     *
     * @return string
     */
    private function getMenuItemTable(): string
    {
        return $this->resource->getTableName(Item::TABLE_NAME_MENU_ITEM);
    }
}
