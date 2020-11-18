<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\Ui\Component\Item\Form;

use AMF\EasyMenuAdminUi\Model\Locator\LocatorInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Options tree for Cms Page Value field
 */
class CmsPageOptions implements OptionSourceInterface
{
    /**
     * @var PageRepositoryInterface
     */
    private $pageRepository;

    /**
     * @var SearchCriteriaBuilderFactory
     */
    private $searchCriteriaBuilderFactory;

    /**
     * @var array|null
     */
    private $cmsPages = null;

    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * CmsPageOptions constructor.
     *
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param LocatorInterface $locator
     * @param PageRepositoryInterface $pageRepository
     */
    public function __construct(
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        LocatorInterface $locator,
        PageRepositoryInterface $pageRepository
    ) {
        $this->pageRepository = $pageRepository;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->locator = $locator;
    }

    /**
     * @inheritdoc
     *
     * @return array|null
     *
     * @throws LocalizedException
     */
    public function toOptionArray()
    {
        if ($this->cmsPages === null) {
            $this->cmsPages = $this->getCmsPages();
        }

        return $this->cmsPages;
    }

    /**
     * Retrieve Cms Page Lists
     *
     * @return array
     *
     * @throws LocalizedException
     */
    private function getCmsPages(): array
    {
        $pageSearchResults = $this->pageRepository->getList($this->getSearchCriteria());
        $pageList = [];

        /** @var \Magento\Cms\Model\Page $page */
        foreach ($pageSearchResults->getItems() as $page) {
            $pageList[] = [
                'value' => $page->getId(),
                'label' => $page->getTitle(),
                'is_active' => $page->isActive(),
            ];
        }

        return $pageList;
    }

    /**
     * Retrieve Search Criteria
     *
     * @return SearchCriteriaInterface
     */
    private function getSearchCriteria(): SearchCriteriaInterface
    {
        $storeId = (string) $this->locator->getStore()->getId();
        /** @var SearchCriteriaBuilder $searchCriteriaBuilder */
        $searchCriteriaBuilder = $this->searchCriteriaBuilderFactory->create();
        $searchCriteriaBuilder->addFilter('store_id', '0, ' . $storeId, 'in');

        return $searchCriteriaBuilder->create();
    }
}
