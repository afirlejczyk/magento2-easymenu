<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Ui\Component\Item\Form;

use AF\EasyMenu\Model\Locator\LocatorInterface;
use Magento\Catalog\Api\Data\CategoryTreeInterface;
use Magento\Catalog\Api\CategoryManagementInterface;
use Magento\Catalog\Model\Category as CategoryModel;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Store\Model\StoreManagerInterface as StoreManagerInterface;

/**
 * Options tree for Category Value field
 */
class CategoryOptions implements OptionSourceInterface
{

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \AF\EasyMenu\Model\Locator\LocatorInterface
     */
    private $locator;

    /**
     * @var CategoryManagementInterface
     */
    private $categoryManagement;

    /**
     * @var array
     */
    private $categoriesTree;

    /**
     * @var array
     */
    private $categoryById = [];

    /**
     * Options constructor.
     *
     * @param CategoryManagementInterface $categoryManagement
     * @param LocatorInterface $locator
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        CategoryManagementInterface $categoryManagement,
        LocatorInterface $locator,
        StoreManagerInterface $storeManager
    ) {
        $this->locator = $locator;
        $this->storeManager = $storeManager;
        $this->categoryManagement = $categoryManagement;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return $this->getCategoriesTree();
    }

    /**
     * Retrieve categories tree
     *
     * @return array
     */
    private function getCategoriesTree()
    {
        if ($this->categoriesTree === null) {
            $this->categoryById = [
                CategoryModel::TREE_ROOT_ID => [
                    'value' => CategoryModel::TREE_ROOT_ID,
                ],
            ];

            $storeRootCategoryId = $this->getRootCategoryId();
            $categoryTree = $this->categoryManagement->getTree($storeRootCategoryId);

            foreach ($categoryTree->getChildrenData() as $child) {
                $this->addChildData($child);
                $this->addChildren($child);
            }

            if (isset($this->categoryById[$storeRootCategoryId])) {
                return $this->categoriesTree = $this->categoryById[$storeRootCategoryId]['optgroup'];
            }

            return $this->categoriesTree = $this->categoryById[CategoryModel::TREE_ROOT_ID];
        }

        return $this->categoriesTree;
    }

    /**
     * @param CategoryTreeInterface $category
     *
     * @return void
     */
    private function addChildren(CategoryTreeInterface $category)
    {
        $children = $category->getChildrenData();

        foreach ($children as $child) {
            $this->addChildData($child);
            $this->addChildren($child);
        }
    }

    /**
     * @param CategoryTreeInterface $child
     *
     * @return void
     */
    private function addChildData(CategoryTreeInterface $child)
    {
        $categoryIds = [
            $child->getId(),
            $child->getParentId(),
        ];

        foreach ($categoryIds as $categoryId) {
            if (!isset($this->categoryById[$categoryId])) {
                $this->categoryById[$categoryId] = ['value' => $categoryId];
            }
        }

        $this->categoryById[$child->getId()]['is_active'] = $child->getIsActive();
        $this->categoryById[$child->getId()]['label'] = $child->getName();
        $this->categoryById[$child->getParentId()]['optgroup'][] = &$this->categoryById[$child->getId()];
    }

    /**
     * @return int
     */
    private function getRootCategoryId()
    {
        /** @var \Magento\Store\Model\Store $store */
        $store = $this->locator->getStore();
        $rootCategoryId = $store->getRootCategoryId();

        if (CategoryModel::ROOT_CATEGORY_ID === $rootCategoryId) {
            return CategoryModel::TREE_ROOT_ID;
        }

        return $rootCategoryId;
    }
}
