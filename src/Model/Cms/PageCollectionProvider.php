<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Model\Cms;

use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\UrlInterface;

/**
 * PageCollectionProvider
 */
class PageCollectionProvider
{
    const VALUE_DELIMITER = ', ';

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
     * PageCollectionProvider constructor.
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
     * @param int $storeId
     * @param array $cmsPageIdentifiers
     *
     * @return array
     */
    public function getCollection($storeId, array $cmsPageIdentifiers)
    {
        $searchCriteria = $this->getSearchCriteria($storeId, $cmsPageIdentifiers);

        $result = $this->pageRepository->getList($searchCriteria);
        $pageUrlList = [];

        foreach ($result->getItems() as $page) {
            $pageUrlList[$page->getId()] = $this->url->getUrl(null, ['_direct' => $page->getIdentifier()]);
        }

        return $pageUrlList;
    }

    /**
     * @param int $storeId
     * @param array $cmsPageIdentifiers
     *
     * @return \Magento\Framework\Api\SearchCriteria
     */
    private function getSearchCriteria($storeId, array $cmsPageIdentifiers)
    {
        $storeIdValue = implode(
            self::VALUE_DELIMITER,
            [
                0,
                $storeId,
            ]
        );

        $searchCriteria = $this->searchCriteriaBuilder;
        $searchCriteria->addFilter(PageInterface::IS_ACTIVE, 1);
        $searchCriteria->addFilter(PageInterface::PAGE_ID, implode(',', $cmsPageIdentifiers), 'in');
        $searchCriteria->addFilter(
            'store_id',
            $storeIdValue,
            'in'
        );

        return $searchCriteria->create();
    }
}
