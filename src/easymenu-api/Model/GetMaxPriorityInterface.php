<?php

namespace AMF\EasyMenuApi\Model;

/**
 * Retrieve max priority base on level hierarchy
 */
interface GetMaxPriorityInterface
{
    /**
     * Get Max Priority
     *
     * @param int $storeId
     * @param int $parentId
     *
     * @return int
     */
    public function execute(int $storeId, int $parentId): int;
}
