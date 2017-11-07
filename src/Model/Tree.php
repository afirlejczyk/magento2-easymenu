<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Model;

use AF\EasyMenu\Model\Item\UrlBuilderInterface;
use AF\EasyMenu\Model\ResourceModel\Item\Collection;
use Magento\Framework\Data\TreeFactory;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Data\Tree\NodeFactory;

/**
 * Menu Items Tree
 */
class Tree
{
    /**
     * @var UrlBuilderInterface
     */
    private $urlItemBuilder;

    /**
     * @var ItemManagementInterface
     */
    private $itemManagement;

    /**
     * @var NodeFactory
     */
    private $nodeFactory;

    /**
     * @var TreeFactory
     */
    private $treeFactory;

    /**
     * Top menu data tree
     *
     * @var \Magento\Framework\Data\Tree\Node
     */
    private $menu;

    /**
     * @var array
     */
    private $urlByItemId = [];

    /**
     * Tree constructor.
     *
     * @param ItemManagementInterface $itemManagement
     * @param UrlBuilderInterface $urlBuilder
     * @param NodeFactory $nodeFactory
     * @param TreeFactory $treeFactory
     */
    public function __construct(
        ItemManagementInterface $itemManagement,
        UrlBuilderInterface $urlBuilder,
        NodeFactory $nodeFactory,
        TreeFactory $treeFactory
    ) {
        $this->urlItemBuilder = $urlBuilder;
        $this->itemManagement = $itemManagement;
        $this->nodeFactory = $nodeFactory;
        $this->treeFactory = $treeFactory;
    }

    /**
     * @param int $storeId
     *
     * @return Node
     */
    public function getMenuTree($storeId)
    {
        /** @var  $collection */
        $collection = $this->getMenuItems($storeId);
        $this->urlByItemId = $this->urlItemBuilder->getUrlForActiveMenuItems($storeId);

        return $this->createItemTree($collection);
    }

    /**
     * @param int $storeId
     *
     * @return ResourceModel\Item\Collection
     */
    public function getMenuItems($storeId)
    {
        return $this->itemManagement->getActiveMenuItems($storeId);
    }

    /**
     * @param Collection $collection
     *
     * @return mixed
     */
    private function createItemTree(Collection $collection)
    {
        $mapping = [0 => $this->getMenu()];

        /** @var Item $item */
        foreach ($collection as $item) {
            $parentId = $item->getParentId();

            if (isset($mapping[$parentId])) {
                $parentCategoryNode = $mapping[$parentId];

                $categoryNode = $this->nodeFactory->create(
                    [
                        'data' => $this->getItemAsArray($item),
                        'idField' => 'id',
                        'tree' => $parentCategoryNode->getTree(),
                        'parent' => $parentCategoryNode,
                    ]
                );

                $parentCategoryNode->addChild($categoryNode);
                $mapping[$item->getId()] = $categoryNode;
            }
        }

        return $mapping[0];
    }

    /**
     * @param Item $item
     *
     * @return array
     */
    public function getItemAsArray(Item $item)
    {
        $url = '';

        if (isset($this->urlByItemId[$item->getId()])) {
            $url = $this->urlByItemId[$item->getId()];
        }

        return [
            'id' => $item->getId(),
            'name' => $item->getName(),
            'open_link_in_new_window' => $item->openLinkInNewWindow(),
            'url' => $url,
        ];
    }

    /**
     * Get menu object.
     *
     * @return Node
     */
    public function getMenu()
    {
        if (!$this->menu) {
            $this->menu = $this->nodeFactory->create(
                [
                    'data' => [],
                    'idField' => 'root',
                    'tree' => $this->treeFactory->create(),
                ]
            );
        }

        return $this->menu;
    }
}
