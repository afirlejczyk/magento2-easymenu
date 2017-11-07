<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Model\Backend;

use AF\EasyMenu\Model\ItemManagementInterface;
use AF\EasyMenu\Model\Item\UrlBuilderInterface;
use Magento\Framework\Data\TreeFactory;
use Magento\Framework\Data\Tree\NodeFactory;

/**
 * Class Tree
 */
class Tree extends \AF\EasyMenu\Model\Tree
{
    /**
     * @var ItemManagementInterface
     */
    private $itemManagement;

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
        parent::__construct($itemManagement, $urlBuilder, $nodeFactory, $treeFactory);

        $this->itemManagement = $itemManagement;
    }

    /**
     * @param int $storeId
     *
     * @return \AF\EasyMenu\Model\ResourceModel\Item\Collection
     */
    public function getMenuItems($storeId)
    {
        return $this->itemManagement->getAllMenuItems($storeId);
    }

    /**
     * @param \AF\EasyMenu\Model\Item $item
     *
     * @return array
     */
    public function getItemAsArray(\AF\EasyMenu\Model\Item $item)
    {
        $cls = 'folder ' . ($item->isActive() ? 'active-category' : 'no-active-category');

        return [
            'text' => $item->getName(),
            'id' => $item->getId(),
            'parent_id' => $item->getParentId(),
            'value' => $item->getValue(),
            'priority' => $item->getPriority(),
            'cls' => $cls,
        ];
    }
}
