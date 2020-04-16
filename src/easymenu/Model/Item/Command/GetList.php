<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model\Item\Command;

use AMF\EasyMenu\Model\ItemSearchResultFactory;
use AMF\EasyMenu\Model\ResourceModel\Item\Collection;
use AMF\EasyMenu\Model\ResourceModel\Item\CollectionFactory;
use AMF\EasyMenuApi\Api\Data\ItemSearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * @inheritdoc
 */
class GetList implements GetListInterface
{
    /**
     * @var CollectionFactory
     */
    private $itemCollectionFactory;

    /**
     * @var ItemSearchResultFactory
     */
    private $itemSearchResultsFactory;

    /**
     * GetList constructor.
     *
     * @param CollectionFactory $stockCollectionFactory
     * @param ItemSearchResultFactory $itemSearchResult
     */
    public function __construct(
        CollectionFactory $stockCollectionFactory,
        ItemSearchResultFactory $itemSearchResult
    ) {
        $this->itemCollectionFactory = $stockCollectionFactory;
        $this->itemSearchResultsFactory = $itemSearchResult;
    }

    /**
     * @inheritdoc
     */
    public function execute(SearchCriteriaInterface $searchCriteria): ItemSearchResultInterface
    {
        /** @var Collection $collection */
        $collection = $this->itemCollectionFactory->create();

        /** @var ItemSearchResultInterface $searchResult */
        $searchResult = $this->itemSearchResultsFactory->create();
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setSearchCriteria($searchCriteria);

        return $searchResult;
    }
}
