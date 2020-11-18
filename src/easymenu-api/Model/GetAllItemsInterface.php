<?php

declare(strict_types=1);

namespace AMF\EasyMenuApi\Model;

use AMF\EasyMenuApi\Api\Data\ItemSearchResultInterface;

/**
 * Retrieve menu items for given store
 */
interface GetAllItemsInterface
{
    /**
     * Get All Items for given store
     *
     * @param int $storeId
     * @param bool $onlyActive
     *
     * @return ItemSearchResultInterface
     */
    public function execute(int $storeId, bool $onlyActive): ItemSearchResultInterface;
}
