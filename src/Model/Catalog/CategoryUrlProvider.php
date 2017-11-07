<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Model\Catalog;

use Magento\CatalogUrlRewrite\Model\CategoryUrlRewriteGenerator;
use Magento\Framework\UrlInterface;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

/**
 * Class CategoryUrlProvider
 */
class CategoryUrlProvider
{

    /**
     * @var UrlFinderInterface
     */
    private $urlFinder;

    /**
     * @var array
     */
    private $categoryUrls;

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
    public function getCategoryUrlList($storeId, array $categoryIds)
    {
        $this->categoryUrls = [];

        $data = [
            UrlRewrite::ENTITY_ID => $categoryIds,
            UrlRewrite::ENTITY_TYPE => CategoryUrlRewriteGenerator::ENTITY_TYPE,
            UrlRewrite::STORE_ID => $storeId,
            UrlRewrite::REDIRECT_TYPE => 0,
        ];

        $urlRewrites = $this->urlFinder->findAllByData($data);

        /** @var \Magento\UrlRewrite\Service\V1\Data\UrlRewrite $urlRewrite */
        foreach ($urlRewrites as $urlRewrite) {
            $this->categoryUrls[$urlRewrite->getEntityId()] = $this->url->getDirectUrl($urlRewrite->getRequestPath());
        }

        foreach ($categoryIds as $categoryId) {
            if (!isset($this->categoryUrls[$categoryId])) {
                $this->categoryUrls[$categoryId] = $this->url->getUrl('catalog/category/view', ['id' => $categoryId]);
            }
        }

        return $this->categoryUrls;
    }
}
