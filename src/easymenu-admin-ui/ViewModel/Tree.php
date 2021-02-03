<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\ViewModel;

use AMF\EasyMenuAdminUi\Model\Locator\LocatorInterface;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use AMF\EasyMenuApi\Model\MenuTreeInterface;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Api\Data\StoreInterface;

/**
 * Class tree provide methods to build items tree in edit view
 */
class Tree implements ArgumentInterface
{
    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * @var SerializerInterface
     */
    private $jsonEncoder;

    /**
     * @var MenuTreeInterface
     */
    private $tree;

    /**
     * @var array
     */
    private $menuTree;

    /**
     * Edit constructor.
     *
     * @param LocatorInterface $locator
     * @param MenuTreeInterface $tree
     * @param SerializerInterface $jsonSerializer
     */
    public function __construct(
        LocatorInterface $locator,
        MenuTreeInterface $tree,
        SerializerInterface $jsonSerializer
    ) {
        $this->locator = $locator;
        $this->jsonEncoder = $jsonSerializer;
        $this->tree = $tree;
    }

    /**
     * Retrieve Store Id
     *
     * @return int
     */
    public function getStoreId(): int
    {
        return (int) $this->getStore()->getId();
    }

    /**
     * Retrieve Item id
     *
     * @return int
     */
    public function getItemId(): int
    {
        return (int) $this->getCurrentItem()->getId();
    }

    /**
     * Get menu json tree
     *
     * @return string
     */
    public function getTreeJson(): string
    {
        $jsonArray = $this->getMenuItemTree();

        return $this->jsonEncoder->serialize($jsonArray);
    }

    /**
     * Get menu tree
     *
     * @return array
     */
    public function getMenuItemTree(): array
    {
        if ($this->menuTree === null) {
            $storeId = $this->getStoreId();
            /** @var Node $menuItems */
            $menuTree = $this->tree->getMenuTree($storeId);
            $this->menuTree = $this->getNodeJson($menuTree);
        }

        return $this->menuTree;
    }

    /**
     * Retrieve Current Store
     *
     * @return StoreInterface
     */
    private function getStore(): StoreInterface
    {
        return $this->locator->getStore();
    }

    /**
     * Retrieve Current Selected Item
     *
     * @return ItemInterface
     */
    private function getCurrentItem(): ItemInterface
    {
        return $this->locator->getMenuItem();
    }

    /**
     * @param Node $item
     *
     * @return array
     */
    private function getNodeJson(Node $item): array
    {
        $children = $item->getChildren();
        $jsonChildren = [];

        /** @var Node $child */
        foreach ($children as $child) {
            $item = $child->getData();

            if ($child->hasChildren()) {
                $item['children'] = $this->getNodeJson($child);
            }

            $jsonChildren[] = $item;
        }

        return $jsonChildren;
    }
}
