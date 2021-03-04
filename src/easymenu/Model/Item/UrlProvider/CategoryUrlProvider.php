<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model\Item\UrlProvider;

use AMF\EasyMenu\Model\Catalog\CategoryUrlFinder;
use AMF\EasyMenu\Model\Item\UrlProviderInterface;
use AMF\EasyMenuApi\Api\Data\ItemInterface;

/**
 * Retrieve urls for category items
 */
class CategoryUrlProvider implements UrlProviderInterface
{
    /**
     * @var CategoryUrlFinder
     */
    private $categoryUrlFinder;

    /**
     * CategoryUrlProvider constructor.
     *
     * @param CategoryUrlFinder $categoryUrlProvider
     */
    public function __construct(CategoryUrlFinder $categoryUrlProvider)
    {
        $this->categoryUrlFinder = $categoryUrlProvider;
    }

    public function loadAll(int $storeId, ItemInterface ...$items): array
    {
        $categoryIds = [];

        foreach ($items as $item) {
            $categoryIds[] = $item->getValue();
        }

        $urlByCategory = $this->loadCategoryUrls($storeId, $categoryIds);
        $url = [];

        foreach ($items as $item) {
            $url[$item->getId()] = $urlByCategory[$item->getValue()] ?? '';
        }

        return $url;
    }

    /**
     * @param int $storeId
     * @param array $categoryIds
     *
     * @return array
     */
    private function loadCategoryUrls(int $storeId, array $categoryIds): array
    {
        return $this->categoryUrlFinder->getCategoryUrlList($storeId, $categoryIds);
    }
}
