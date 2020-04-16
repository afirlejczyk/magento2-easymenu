<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model;

use AMF\EasyMenuApi\Api\Data\ItemInterface;
use AMF\EasyMenuApi\Model\BuildTreeInterface;
use AMF\EasyMenuApi\Model\Item\ConvertToArrayInterface;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Framework\Data\TreeFactory;

/**
 * {@inheritDoc}
 */
class BuildMenuTree implements BuildTreeInterface
{
    /**
     * @var ConvertToArrayInterface
     */
    private $convertToArray;

    /**
     * @var NodeFactory
     */
    private $nodeFactory;

    /**
     * @var TreeFactory
     */
    private $treeFactory;

    /**
     * BuildMenuTree constructor.
     *
     * @param ConvertToArrayInterface $convertItemToArray
     * @param NodeFactory $nodeFactory
     * @param TreeFactory $treeFactory
     */
    public function __construct(
        ConvertToArrayInterface $convertItemToArray,
        NodeFactory $nodeFactory,
        TreeFactory $treeFactory
    ) {
        $this->convertToArray = $convertItemToArray;
        $this->nodeFactory = $nodeFactory;
        $this->treeFactory = $treeFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function buildMenuTree(\AMF\EasyMenuApi\Api\Data\ItemSearchResultInterface $itemSearchResult): Node
    {
        $mapping = [0 => $this->getMenu()];
        $items = $itemSearchResult->getItems();

        /** @var Item $item */
        foreach ($items as $item) {
            $parentId = $item->getParentId();

            if (isset($mapping[$parentId])) {
                $parentCategoryNode = $mapping[$parentId];
                $itemNode = $this->createMenuNode($item, $parentCategoryNode);

                $parentCategoryNode->addChild($itemNode);
                $mapping[$item->getId()] = $itemNode;
            }
        }

        return $mapping[0];
    }

    /**
     * Get menu object.
     *
     * @return Node
     */
    private function getMenu(): Node
    {
        return $this->nodeFactory->create(
            [
                'data' => [],
                'idField' => 'root',
                'tree' => $this->treeFactory->create(),
            ]
        );
    }

    /**
     * Create Menu Node
     *
     * @param ItemInterface $item
     * @param Node $parentItemNode
     *
     * @return Node
     */
    private function createMenuNode(ItemInterface $item, Node $parentItemNode): Node
    {
        return $this->nodeFactory->create(
            [
                'data' => $this->convertToArray->execute($item),
                'idField' => 'id',
                'tree' => $parentItemNode->getTree(),
                'parent' => $parentItemNode,
            ]
        );
    }
}
