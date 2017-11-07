<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Block\Adminhtml;

use AF\EasyMenu\Model\ItemManagementInterface;
use AF\EasyMenu\Model\Backend\Tree as MenuItemTree;
use AF\EasyMenu\Model\Locator\LocatorInterface;
use Magento\Backend\Block\Template;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Serialize\SerializerInterface;

/**
 * Class Tree
 */
class Tree extends Template
{

    const BASE_URL = 'easymenu/item/';

    /**
     * @var ItemManagementInterface
     */
    private $itemManagement;

    /**
     * @var \AF\EasyMenu\Model\Locator\LocatorInterface
     */
    private $locator;

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    private $jsonEncoder;

    /**
     * @var MenuItemTree
     */
    private $tree;

    /**
     * @var array|null
     */
    private $menuTree;

    /**
     * Tree constructor.
     *
     * @param Template\Context $context
     * @param LocatorInterface $locator
     * @param ItemManagementInterface $itemManagement
     * @param MenuItemTree $tree
     * @param SerializerInterface $jsonSerializer
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        LocatorInterface $locator,
        ItemManagementInterface $itemManagement,
        MenuItemTree $tree,
        SerializerInterface $jsonSerializer,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->locator = $locator;
        $this->itemManagement = $itemManagement;
        $this->jsonEncoder = $jsonSerializer;
        $this->tree = $tree;
    }

    /**
     * @inheritdoc
     */
    protected function _prepareLayout()
    {
        $addUrl = $this->getUrl(
            "*/*/add",
            [
                '_current' => false,
                'item_id' => null,
                'store' => $this->getStoreId(),
                '_query' => false,
            ]
        );

        $this->addChild(
            'add_main_button',
            \Magento\Backend\Block\Widget\Button::class,
            [
                'label' => __('Add Main Menu Item'),
                'onclick' => "addNew('" . $addUrl . "', true)",
                'class' => 'add',
                'id' => 'add_main_item_button',
            ]
        );

        $this->addChild(
            'add_sub_button',
            \Magento\Backend\Block\Widget\Button::class,
            [
                'label' => __('Add Sub Menu Item'),
                'onclick' => "addNew('" . $addUrl . "', false)",
                'class' => 'add',
                'id' => 'add_sub_item_button',
            ]
        );

        return parent::_prepareLayout();
    }

    /**
     * @return string
     */
    public function getAddSubButtonHtml()
    {
        return $this->getChildHtml('add_sub_button');
    }

    /**
     * @return string
     */
    public function getMainButtonHtml()
    {
        return $this->getChildHtml('add_main_button');
    }

    /**
     * Retrieve move item url
     *
     * @return string
     */
    public function getMoveUrl()
    {
        return $this->getUrl(self::BASE_URL . 'move');
    }

    /**
     * Retrieve item edit url
     *
     * @return string
     */
    public function getEditUrl()
    {
        return $this->getUrl(self::BASE_URL . 'edit', ['_query' => false]);
    }

    /**
     * Retrieve Item id
     *
     * @return int
     */
    public function getItemId()
    {
        $currentItemId = $this->getCurrentItem()->getId();

        if ($currentItemId) {
            return $currentItemId;
        }

        return 0;
    }

    /**
     * @return \AF\EasyMenu\Api\Data\ItemInterface
     */
    public function getCurrentItem()
    {
        return $this->locator->getMenuItem();
    }

    /**
     * Get menu json tree
     *
     * @return string
     */
    public function getTreeJson()
    {
        $jsonArray = $this->getNodeAsArray();

        return $this->jsonEncoder->serialize($jsonArray);
    }

    /**
     * @return array
     */
    public function getNodeAsArray()
    {
        return $this->getMenuItemTree();
    }

    /**
     * Get menu tree
     *
     * @return array
     */
    public function getMenuItemTree()
    {
        if (null === $this->menuTree) {
            $storeId = $this->getStoreId();
            /** @var \Magento\Framework\Data\Tree\Node $menuItems */
            $menuTree = $this->tree->getMenuTree($storeId);
            $this->menuTree = $this->getNodeJson($menuTree);
        }

        return $this->menuTree;
    }

    /**
     * @param \Magento\Framework\Data\Tree\Node $item
     *
     * @return array
     */
    private function getNodeJson(Node $item)
    {
        $children = $item->getChildren();
        $jsonChildren = [];

        /** @var \Magento\Framework\Data\Tree\Node $child */
        foreach ($children as $child) {
            $json = $child->getData();

            if ($child->hasChildren()) {
                $json['children'] = $this->getNodeJson($child);
            }

            $jsonChildren[] = $json;
        }

        return $jsonChildren;
    }

    /**
     * @return int
     */
    public function getStoreId()
    {
        return $this->getStore()->getId();
    }

    /**
     * @return \Magento\Store\Api\Data\StoreInterface
     */
    public function getStore()
    {
        return $this->locator->getStore();
    }
}
