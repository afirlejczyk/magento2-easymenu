<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model\Cms;

use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\UrlInterface;

/**
 * Responsible to load URLs for given pages
 */
class PageUrlProvider
{
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;

    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * PageUrlProvider constructor.
     *
     * @param UrlInterface $url
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param PageRepositoryInterface $pageRepository
     */
    public function __construct(
        UrlInterface $url,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        PageRepositoryInterface $pageRepository
    ) {
        $this->pageRepository = $pageRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->url = $url;
    }

    /**
     * Return List of cms page urls group by page ids
     *
     * @param int $storeId
     * @param array $cmsPageIdentifiers
     *
     * @return array
     */
    public function execute(int $storeId, array $cmsPageIdentifiers): array
    {
        $searchCriteria = $this->buildSearchCriteria($storeId, $cmsPageIdentifiers);
        $result = $this->pageRepository->getList($searchCriteria);
        $pageUrlList = [];

        foreach ($result->getItems() as $page) {
            $pageUrlList[$page->getId()] = $this->url->getUrl(null, ['_direct' => $page->getIdentifier()]);
        }

        return $pageUrlList;
    }

    /**
     * Build Search Criteria
     *
     * @param int $storeId
     * @param array $cmsPageIdentifiers
     *
     * @return SearchCriteria
     */
    private function buildSearchCriteria(int $storeId, array $cmsPageIdentifiers): SearchCriteria
    {
        $searchCriteria = $this->searchCriteriaBuilder;
        $searchCriteria->addFilter(PageInterface::IS_ACTIVE, 1);
        $searchCriteria->addFilter(PageInterface::PAGE_ID, $cmsPageIdentifiers, 'in');
        $searchCriteria->addFilter('store_id', [0, $storeId], 'in');

        return $searchCriteria->create();
    }
}
