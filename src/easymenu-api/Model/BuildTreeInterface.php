<?php

declare(strict_types=1);

namespace AMF\EasyMenuApi\Model;

use AMF\EasyMenuApi\Api\Data\ItemSearchResultInterface;
use Magento\Framework\Data\Tree\Node;

/**
 * Build Node Edit for given items
 */
interface BuildTreeInterface
{
    /**
     * Build Menu Edit
     *
     * @param ItemSearchResultInterface $itemSearchResult
     *
     * @return Node
     */
    public function buildMenuTree(ItemSearchResultInterface $itemSearchResult): Node;
}
