<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Ui\Component\Item\Form;

use AF\EasyMenu\Model\Locator\LocatorInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Options tree for Cms Page Value field
 */
class CmsPageOptions implements OptionSourceInterface
{

    /**
     * @PageRepositoryInterface
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
     * @var \AF\EasyMenu\Model\Locator\LocatorInterface
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
     * @return array
     */
    public function toOptionArray()
    {
        return $this->getCmsPages();
    }

    /**
     * @return array
     */
    protected function getCmsPages()
    {
        if (null === $this->cmsPages) {
            $cmsById = [];

            $searchCriteria = $this->getSearchCriteria();
            $collection = $this->pageRepository->getList($searchCriteria);

            /** @var \Magento\Cms\Model\Page $page */
            foreach ($collection->getItems() as $page) {
                $pageId = $page->getId();

                $cmsById[] = [
                    'value' => $pageId,
                    'label' => $page->getTitle(),
                    'is_active' => $page->isActive(),
                ];
            }

            $this->cmsPages = $cmsById;
        }

        return $this->cmsPages;
    }

    /**
     * @return SearchCriteria
     */
    private function getSearchCriteria()
    {
        $storeId = (string) $this->locator->getStore()->getId();

        $searchCriteria = $this->searchCriteriaBuilder;
        $searchCriteria->addFilter('store_id', '0, ' . $storeId, 'in');

        return $searchCriteria->create();
    }
}
