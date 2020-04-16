<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model\Backend;

use AMF\EasyMenuApi\Model\BuildTreeInterface;
use AMF\EasyMenuApi\Model\GetAllItemsInterface;
use AMF\EasyMenuApi\Model\MenuTreeInterface;
use Magento\Framework\Data\Tree\Node;

/**
 * {@inheritDoc}
 */
class Tree implements MenuTreeInterface
{
    /**
     * @var GetAllItemsInterface
     */
    private $getItems;

    /**
     * @var BuildTreeInterface
     */
    private $buildTree;

    /**
     * Edit constructor.
     *
     * @param GetAllItemsInterface $itemManagement
     * @param BuildTreeInterface $buildTree
     */
    public function __construct(
        GetAllItemsInterface $itemManagement,
        BuildTreeInterface $buildTree
    ) {
        $this->buildTree = $buildTree;
        $this->getItems = $itemManagement;
    }

    /**
     * @inheritdoc
     */
    public function getMenuTree(int $storeId): Node
    {
        $menuItems = $this->getItems->execute($storeId);

        return $this->buildTree->buildMenuTree($menuItems);
    }
}
