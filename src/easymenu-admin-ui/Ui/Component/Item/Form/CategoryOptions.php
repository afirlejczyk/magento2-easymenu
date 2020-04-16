<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\Ui\Component\Item\Form;

use AMF\EasyMenuAdminUi\Model\Locator\LocatorInterface;
use Magento\Catalog\Model\Category as CategoryModel;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Options tree for Category Value field
 */
class CategoryOptions implements OptionSourceInterface
{
    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * @var CategoryTreeBuilder
     */
    private $categoryTreeBuilder;

    /**
     * @var array
     */
    private $categoriesTree;

    /**
     * Options constructor.
     *
     * @param CategoryTreeBuilder $categoryTreeBuilder
     * @param LocatorInterface $locator
     */
    public function __construct(
        CategoryTreeBuilder $categoryTreeBuilder,
        LocatorInterface $locator
    ) {
        $this->locator = $locator;
        $this->categoryTreeBuilder = $categoryTreeBuilder;
    }

    /**
     * @inheritDoc
     *
     * @return array
     * @throws NoSuchEntityException
     */
    public function toOptionArray()
    {
        if ($this->categoriesTree === null) {
            $this->categoriesTree = $this->getCategoriesTree();
        }

        return $this->categoriesTree;
    }

    /**
     * Retrieve Categories Edit
     *
     * @return array
     *
     * @throws NoSuchEntityException
     */
    private function getCategoriesTree(): array
    {
        $storeRootCategoryId = $this->getRootCategoryId();
        $categoryById = $this->categoryTreeBuilder->build($storeRootCategoryId);

        if (isset($categoryById[$storeRootCategoryId])) {
            return $categoryById[$storeRootCategoryId]['optgroup'];
        }

        return $categoryById[CategoryModel::TREE_ROOT_ID]['optgroup'];
    }

    /**
     * Retrieve Root Category ID
     *
     * @return int
     */
    private function getRootCategoryId(): int
    {
        $store = $this->locator->getStore();
        $rootCategoryId = (int) $store->getRootCategoryId();

        return $rootCategoryId === CategoryModel::ROOT_CATEGORY_ID
            ? CategoryModel::TREE_ROOT_ID
            : $rootCategoryId;
    }
}
