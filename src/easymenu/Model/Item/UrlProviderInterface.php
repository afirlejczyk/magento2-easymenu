<?php declare(strict_types=1);

namespace AMF\EasyMenu\Model\Item;

use AMF\EasyMenuApi\Api\Data\ItemInterface;

/**
 * Interface UrlProviderInterface
 */
interface UrlProviderInterface
{
    /**
     * Load urls for given items
     *
     * @param int $storeId
     * @param ItemInterface ...$items
     *
     * @return array
     */
    public function loadAll(int $storeId, ItemInterface ...$items);
}
