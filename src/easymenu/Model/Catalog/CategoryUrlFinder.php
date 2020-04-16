<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model\Catalog;

use Magento\CatalogUrlRewrite\Model\CategoryUrlRewriteGenerator;
use Magento\Framework\UrlInterface;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

/**
 * Class responsible to find url rewrite for given categories
 */
class CategoryUrlFinder
{
    /**
     * @var UrlFinderInterface
     */
    private $urlFinder;

    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * CategoryUrlFinder constructor.
     *
     * @param UrlInterface $url
     * @param UrlFinderInterface $urlFinder
     */
    public function __construct(
        UrlInterface $url,
        UrlFinderInterface $urlFinder
    ) {
        $this->urlFinder = $urlFinder;
        $this->url = $url;
    }

    /**
     * @param int $storeId
     * @param array $categoryIds
     *
     * @return array
     */
    public function getCategoryUrlList(int $storeId, array $categoryIds): array
    {
        $categoryUrls = [];
        $urlRewrites = $this->findUrlRewrite($storeId, $categoryIds);

        foreach ($urlRewrites as $urlRewrite) {
            $categoryUrls[$urlRewrite->getEntityId()] = $this->url->getDirectUrl($urlRewrite->getRequestPath());
        }

        foreach ($categoryIds as $categoryId) {
            $categoryUrls[$categoryId] = $categoryUrls[$categoryId] ??
                $this->url->getUrl('catalog/category/view', ['id' => $categoryId]);
        }

        return $categoryUrls;
    }

    /**
     * Find url rewrite for given categories and store
     *
     * @param int $storeId
     * @param array $categoryIds
     *
     * @return array
     */
    private function findUrlRewrite(int $storeId, array $categoryIds): array
    {
        $data = [
            UrlRewrite::ENTITY_ID => $categoryIds,
            UrlRewrite::ENTITY_TYPE => CategoryUrlRewriteGenerator::ENTITY_TYPE,
            UrlRewrite::STORE_ID => $storeId,
            UrlRewrite::REDIRECT_TYPE => 0,
        ];

        return $this->urlFinder->findAllByData($data);
    }
}
