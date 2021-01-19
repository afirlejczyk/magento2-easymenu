<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model;

use AMF\EasyMenuApi\Model\BuildTreeInterface;
use AMF\EasyMenuApi\Model\GetItemsByStoreIdInterface;
use AMF\EasyMenuApi\Model\MenuTreeInterface;
use Magento\Framework\Data\Tree\Node;

/**
 * Menu Items Edit
 */
class Tree implements MenuTreeInterface
{
    /**
     * @var GetItemsByStoreIdInterface
     */
    private $getActiveItems;

    /**
     * @var BuildTreeInterface
     */
    private $buildTree;

    /**
     * @var \Magento\Framework\Data\Tree\Node
     */
    private $menu;

    /**
     * Tree constructor.
     *
     * @param GetItemsByStoreIdInterface $getActiveItems
     * @param BuildTreeInterface $buildTree
     */
    public function __construct(
        GetItemsByStoreIdInterface $getActiveItems,
        BuildTreeInterface $buildTree
    ) {
        $this->buildTree = $buildTree;
        $this->getActiveItems = $getActiveItems;
    }

    /**
     * Retrieve Menu Edit
     *
     * @param int $storeId
     *
     * @return Node
     */
    public function getMenuTree(int $storeId): Node
    {
        if ($this->menu === null) {
            $itemList = $this->getActiveItems->getActive($storeId);
            $this->menu = $this->buildTree->buildMenuTree($itemList);
        }

        return $this->menu;
    }
}
