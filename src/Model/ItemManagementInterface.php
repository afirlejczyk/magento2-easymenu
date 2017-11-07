<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Model;

use AF\EasyMenu\Model\ResourceModel\Item\Collection as ItemCollection;

/**
 * Interface ItemManagementInterface
 */
interface ItemManagementInterface
{
    /**
     * @param int $storeId
     *
     * @return ItemCollection
     */
    public function getActiveMenuItems($storeId);

    /**
     * @param int $storeId
     *
     * @return ItemCollection
     */
    public function getAllMenuItems($storeId);

    /**
     * @param int $parentId
     * @param bool $object
     *
     * @return ItemCollection|array
     */
    public function getChildren($parentId, $object = false);
}
