<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model;

use AMF\EasyMenuApi\Model\BuildTreeInterface;
use AMF\EasyMenuApi\Model\GetAllItemsInterface;
use AMF\EasyMenuApi\Model\MenuTreeInterface;
use Magento\Framework\Data\Tree\Node;

/**
 * Menu Items Edit
 */
class Tree implements MenuTreeInterface
{
    /**
     * @var GetAllItemsInterface
     */
    private $itemManagement;

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
     * @param GetAllItemsInterface $getAllItems
     * @param BuildTreeInterface $buildTree
     */
    public function __construct(
        GetAllItemsInterface $getAllItems,
        BuildTreeInterface $buildTree
    ) {
        $this->buildTree = $buildTree;
        $this->itemManagement = $getAllItems;
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
            $itemList = $this->itemManagement->execute($storeId, true);
            $this->menu = $this->buildTree->buildMenuTree($itemList);
        }

        return $this->menu;
    }
}
