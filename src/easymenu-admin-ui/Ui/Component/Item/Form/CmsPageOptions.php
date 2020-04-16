<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\Ui\Component\Item\Form;

use AMF\EasyMenuAdminUi\Model\Locator\LocatorInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
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
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

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
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param LocatorInterface $locator
     * @param PageRepositoryInterface $pageRepository
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        LocatorInterface $locator,
        PageRepositoryInterface $pageRepository
    ) {
        $this->pageRepository = $pageRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
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
        $pageList = [];

        $searchCriteria = $this->getSearchCriteria();
        $collection = $this->pageRepository->getList($searchCriteria);

        /** @var \Magento\Cms\Model\Page $page */
        foreach ($collection->getItems() as $page) {
            $pageId = $page->getId();

            $pageList[] = [
                'value' => $pageId,
                'label' => $page->getTitle(),
                'is_active' => $page->isActive(),
            ];
        }

        return $pageList;
    }

    /**
     * Retrieve Search Criteria
     *
     * @return SearchCriteria
     */
    private function getSearchCriteria(): SearchCriteria
    {
        $storeId = (string) $this->locator->getStore()->getId();
        $searchCriteria = $this->searchCriteriaBuilder;
        $searchCriteria->addFilter('store_id', '0, ' . $storeId, 'in');

        return $searchCriteria->create();
    }
}
