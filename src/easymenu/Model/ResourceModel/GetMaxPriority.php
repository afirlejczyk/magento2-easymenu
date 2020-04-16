<?php

namespace AMF\EasyMenu\Model\ResourceModel;

use AMF\EasyMenuApi\Model\GetMaxPriorityInterface;
use Magento\Framework\App\ResourceConnection;

/**
 * Class responsible for calculating
 */
class GetMaxPriority implements GetMaxPriorityInterface
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
     * Get Max Priority
     *
     * @param int $storeId
     * @param int $parentId
     *
     * @return int
     */
    public function execute(int $storeId, int $parentId): int
    {
        $table = $this->resource->getTableName(Item::TABLE_NAME_MENU_ITEM);
        $connection = $this->resource->getConnection();

        $select = $connection->select()->from($table, []);

        if ($parentId) {
            $select->where('parent_id = ?', $parentId);
            $select->columns(['max' => new \Zend_Db_Expr('MAX(priority)')]);
        } else {
            $select->where('store_id = ?', $storeId);
            $select->columns(['count' => new \Zend_Db_Expr('count(priority)')]);
        }

        $result = $connection->fetchOne($select);

        if (null === $result) {
            return 0;
        }

        return (int) $result;
    }
}
