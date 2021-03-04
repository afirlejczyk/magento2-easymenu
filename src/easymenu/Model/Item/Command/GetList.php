<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model\Item\Command;

use AMF\EasyMenu\Model\ItemSearchResultFactory;
use AMF\EasyMenu\Model\ResourceModel\Item\Collection;
use AMF\EasyMenu\Model\ResourceModel\Item\CollectionFactory;
use AMF\EasyMenuApi\Api\Data\ItemSearchResultInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

class GetList implements GetListInterface
{
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var CollectionFactory
     */
    private $itemCollectionFactory;

    /**
     * @var ItemSearchResultFactory
     */
    private $itemSearchResultsFactory;

    public function __construct(
        CollectionProcessorInterface $collectionProcessor,
        CollectionFactory $itemCollectionFactory,
        ItemSearchResultFactory $itemSearchResult
    ) {
        $this->collectionProcessor = $collectionProcessor;
        $this->itemCollectionFactory = $itemCollectionFactory;
        $this->itemSearchResultsFactory = $itemSearchResult;
    }

    public function execute(SearchCriteriaInterface $searchCriteria): ItemSearchResultInterface
    {
        /** @var Collection $collection */
        $collection = $this->itemCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var ItemSearchResultInterface $searchResult */
        $searchResult = $this->itemSearchResultsFactory->create();
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setSearchCriteria($searchCriteria);

        return $searchResult;
    }
}
