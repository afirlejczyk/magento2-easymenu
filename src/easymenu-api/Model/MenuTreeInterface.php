<?php

declare(strict_types=1);

namespace AMF\EasyMenuApi\Model;

use Magento\Framework\Data\Tree\Node;

/**
 * Retrieve menu tree for store
 */
interface MenuTreeInterface
{
    /**
     * @param int $storeId
     *
     * @return Node
     */
    public function getMenuTree(int $storeId): Node;
}
