<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\Ui\Component\Item\Form;

use Magento\Catalog\Api\CategoryManagementInterface;
use Magento\Catalog\Api\Data\CategoryTreeInterface;
use Magento\Catalog\Model\Category as CategoryModel;
use Magento\Framework\App\Cache\Type\Block;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\SerializerInterface;

/**
 * Class CategoryTreeBuilder is responsible to build category tree options
 */
class CategoryTreeBuilder
{
    /**
     * @const string
     */
    const CATEGORY_TREE_ID = 'EASYMENU_CATEGORY_TREE';

    /**
     * @var CategoryManagementInterface
     */
    private $categoryManagement;

    /**
     * @var CacheInterface
     */
    private $cacheManager;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var array
     */
    private $categoryById = [];

    /**
     * CategoryTreeBuilder constructor.
     *
     * @param CacheInterface $cacheManager
     * @param SerializerInterface $serializer
     * @param CategoryManagementInterface $categoryManagement
     */
    public function __construct(
        CacheInterface $cacheManager,
        SerializerInterface $serializer,
        CategoryManagementInterface $categoryManagement
    ) {
        $this->cacheManager = $cacheManager;
        $this->serializer = $serializer;
        $this->categoryManagement = $categoryManagement;
    }

    /**
     * @param int $storeRootCategoryId
     *
     * @return array
     *
     * @throws NoSuchEntityException
     */
    public function build(int $storeRootCategoryId): array
    {
        $cachedCategoriesTree = $this->cacheManager->load($this->getCategoriesTreeCacheId($storeRootCategoryId));

        if (!empty($cachedCategoriesTree)) {
            return $this->serializer->unserialize($cachedCategoriesTree);
        }

        $this->categoryById = [
            CategoryModel::TREE_ROOT_ID => [
                'value' => CategoryModel::TREE_ROOT_ID,
                'optgroup' => [],
            ],
        ];

        $categoryTree = $this->categoryManagement->getTree($storeRootCategoryId);

        foreach ($categoryTree->getChildrenData() as $child) {
            $this->addChildData($child);
            $this->addChildren($child);
        }

        $this->cacheManager->save(
            $this->serializer->serialize($this->categoryById),
            $this->getCategoriesTreeCacheId($storeRootCategoryId),
            [
                CategoryModel::CACHE_TAG,
                Block::CACHE_TAG
            ]
        );

        return $this->categoryById;
    }

    /**
     * Get cache id for categories tree.
     *
     * @param int $rootStoreId
     *
     * @return string
     */
    private function getCategoriesTreeCacheId(int $rootStoreId) : string
    {
        return sprintf('%s_%s', self::CATEGORY_TREE_ID, (string) $rootStoreId);
    }

    /**
     * Add Children
     *
     * @param CategoryTreeInterface $category
     *
     * @return void
     */
    private function addChildren(CategoryTreeInterface $category): void
    {
        $children = $category->getChildrenData();

        foreach ($children as $child) {
            $this->addChildData($child);
            $this->addChildren($child);
        }
    }

    /**
     * Add child data
     *
     * @param CategoryTreeInterface $child
     *
     * @return void
     */
    private function addChildData(CategoryTreeInterface $child): void
    {
        $this->categoryById[$child->getId()]['value'] = (int) $child->getId();
        $this->categoryById[$child->getId()]['is_active'] = $child->getIsActive();
        $this->categoryById[$child->getId()]['label'] = $child->getName();

        $this->categoryById[$child->getParentId()]['value'] = (int) $child->getParentId();
        $this->categoryById[$child->getParentId()]['optgroup'][] = &$this->categoryById[$child->getId()];
    }
}
