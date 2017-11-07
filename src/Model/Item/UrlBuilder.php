<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Model\Item;

use AF\EasyMenu\Model\Cms\PageCollectionProvider;
use AF\EasyMenu\Model\Catalog\CategoryUrlProvider;
use AF\EasyMenu\Model\Item;
use AF\EasyMenu\Model\ItemManagementInterface;
use Magento\Framework\UrlInterface;

/**
 * Class UrlBuilder
 */
class UrlBuilder implements UrlBuilderInterface
{

    /**
     * @var CategoryUrlProvider
     */
    private $categoryUrlProvider;

    /**
     * @var PageCollectionProvider
     */
    private $cmsPageCollectionProvider;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var ItemManagementInterface
     */
    private $itemManagement;

    /**
     * @var array
     */
    private $cmsPageUrlList;

    /**
     * @var array
     */
    private $categoryUrlList;

    /**
     * UrlBuilder constructor.
     *
     * @param ItemManagementInterface $itemManagement
     * @param UrlInterface $urlBuilder
     * @param PageCollectionProvider $pageCollectionProvider
     * @param CategoryUrlProvider $categoryUrlProvider
     */
    public function __construct(
        ItemManagementInterface $itemManagement,
        UrlInterface $urlBuilder,
        PageCollectionProvider $pageCollectionProvider,
        CategoryUrlProvider $categoryUrlProvider
    ) {
        $this->itemManagement = $itemManagement;
        $this->urlBuilder = $urlBuilder;
        $this->cmsPageCollectionProvider = $pageCollectionProvider;
        $this->categoryUrlProvider = $categoryUrlProvider;
    }

    /**
     * @param int $storeId
     *
     * @return array
     */
    public function getUrlForActiveMenuItems($storeId)
    {
        $this->prepareCategoryAndCmsPageUrls($storeId);

        $collection = $this->itemManagement->getActiveMenuItems($storeId);
        $urls = [];

        /** @var Item $item */
        foreach ($collection as $item) {
            $value = $item->getValue();
            $url = '';

            if ($item->isCmsPageItem() && isset($this->cmsPageUrlList[$value])) {
                $url = $this->cmsPageUrlList[$value];
            } elseif ($item->isCategoryItem() && isset($this->categoryUrlList[$value])) {
                $url = $this->categoryUrlList[$value];
            } elseif ($item->isCustomLink()) {
                $url = $this->prepareCustomLinkUrl($value);
            }

            $urls[$item->getId()] = $url;
        }

        return $urls;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private function prepareCustomLinkUrl($value)
    {
        if (preg_match("@^https?://@", $value)) {
            $url = $value;
        } else {
            $url = $this->urlBuilder->getBaseUrl();

            if ('/' !== $value) {
                $url .= $value;
            }
        }

        return $url;
    }

    /**
     * @param int $storeId
     */
    private function prepareCategoryAndCmsPageUrls($storeId)
    {
        $collection = $this->itemManagement->getActiveMenuItems($storeId);
        $cmsPageIds = [];
        $categoryIds = [];

        /** @var Item $item */
        foreach ($collection as $item) {
            if ($item->isCategoryItem()) {
                $categoryIds[] = $item->getValue();
            } elseif ($item->isCmsPageItem()) {
                $cmsPageIds[] = $item->getValue();
            }
        }

        $this->prepareCategoryUrlList($storeId, $categoryIds);
        $this->prepareCmsPageList($storeId, $cmsPageIds);
    }

    /**
     * @param int $storeId
     * @param array $cmsPageIds
     *
     * @return array
     */
    private function prepareCmsPageList($storeId, array $cmsPageIds)
    {
        if (null === $this->cmsPageUrlList) {
            $this->cmsPageUrlList = $this->cmsPageCollectionProvider->getCollection($storeId, $cmsPageIds);
        }

        return $this->cmsPageUrlList;
    }

    /**
     * @param int $storeId
     * @param array $categoryIds
     *
     * @return array
     */
    private function prepareCategoryUrlList($storeId, array $categoryIds)
    {
        if (null === $this->categoryUrlList) {
            $this->categoryUrlList = $this->categoryUrlProvider->getCategoryUrlList($storeId, $categoryIds);
        }

        return $this->categoryUrlList;
    }
}
