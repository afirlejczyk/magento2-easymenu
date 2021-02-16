<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\Model\Backend;

use AMF\EasyMenuApi\Model\BuildTreeInterface;
use AMF\EasyMenuApi\Model\GetItemsByStoreIdInterface;
use AMF\EasyMenuApi\Model\MenuTreeInterface;
use Magento\Framework\Data\Tree\Node;

/**
 * {@inheritDoc}
 */
class Tree implements MenuTreeInterface
{
    /**
     * @var GetItemsByStoreIdInterface
     */
    private $getItemsByStoreId;

    /**
     * @var BuildTreeInterface
     */
    private $buildTree;

    /**
     * Edit constructor.
     *
     * @param GetItemsByStoreIdInterface $getItemsByStoreId
     * @param BuildTreeInterface $buildTree
     */
    public function __construct(
        GetItemsByStoreIdInterface $getItemsByStoreId,
        BuildTreeInterface $buildTree
    ) {
        $this->buildTree = $buildTree;
        $this->getItemsByStoreId = $getItemsByStoreId;
    }

    /**
     * @inheritdoc
     */
    public function getMenuTree(int $storeId): Node
    {
        $itemsSearchResult = $this->getItemsByStoreId->getAll($storeId);

        return $this->buildTree->buildMenuTree($itemsSearchResult);
    }
}
