<?php

declare(strict_types=1);

namespace AMF\EasyMenuApi\Model;

use AMF\EasyMenuApi\Api\Data\ItemSearchResultInterface;

/**
 * Retrieve menu items for given store
 */
interface GetItemsByStoreIdInterface
{
    /**
     * Get All active menu items for given store
     *
     * @param int $storeId
     *
     * @return ItemSearchResultInterface
     */
    public function getActive(int $storeId): ItemSearchResultInterface;

    public function getAll(int $storeId): ItemSearchResultInterface;
}
