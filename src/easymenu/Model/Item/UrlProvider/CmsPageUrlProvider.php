<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model\Item\UrlProvider;

use AMF\EasyMenu\Model\Cms\PageUrlProvider;
use AMF\EasyMenu\Model\Item\UrlProviderInterface;
use AMF\EasyMenuApi\Api\Data\ItemInterface;

/**
 * Load url for cms pages menu items
 */
class CmsPageUrlProvider implements UrlProviderInterface
{
    /**
     * @var PageUrlProvider
     */
    private $cmsPageUrlProvider;

    /**
     * CmsPageUrlProvider constructor.
     *
     * @param PageUrlProvider $pageUrlProvider
     */
    public function __construct(PageUrlProvider $pageUrlProvider)
    {
        $this->cmsPageUrlProvider = $pageUrlProvider;
    }

    public function loadAll(int $storeId, ItemInterface ...$items): array
    {
        $cmsPageIds = $this->getCmsPagesIds(...$items);
        $cmsPageUrls = $this->cmsPageUrlProvider->execute(
            $storeId,
            $cmsPageIds
        );

        $url = [];

        foreach ($items as $item) {
            $url[$item->getId()] = $cmsPageUrls[$item->getValue()] ?? '';
        }

        return $url;
    }

    /**
     * @param ItemInterface ...$items
     *
     * @return array
     */
    private function getCmsPagesIds(ItemInterface ...$items)
    {
        $categoryIds = [];

        foreach ($items as $item) {
            $categoryIds[] = $item->getValue();
        }

        return $categoryIds;
    }
}
