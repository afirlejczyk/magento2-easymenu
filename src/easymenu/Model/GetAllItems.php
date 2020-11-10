<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model;

use AMF\EasyMenu\Model\ResourceModel\Item\Collection;
use AMF\EasyMenu\Model\ResourceModel\Item\CollectionFactory;
use AMF\EasyMenuApi\Api\Data\ItemSearchResultInterface;
use AMF\EasyMenuApi\Model\GetAllItemsInterface;

/**
 * {@inheritDoc}
 */
class GetAllItems implements GetAllItemsInterface
{
    /**
     * @var ItemSearchResultInterface[]
     */
    private $cacheItemInstance = [];

    /**
     * @var CollectionFactory
     */
    private $itemCollectionFactory;

    /**
     * @var ItemSearchResultFactory
     */
    private $itemSearchResultsFactory;

    /**
     * GetAllItems constructor.
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
     * Get All Items for given store
     *
     * @param int $storeId
     * @param bool $onlyActive
     *
     * @return ItemSearchResultInterface
     */
    public function execute(int $storeId, bool $onlyActive): ItemSearchResultInterface
    {
        if (! isset($this->cacheItemInstance[$storeId])) {
            $collection = $this->getCollection($storeId, $onlyActive);
            $this->cacheItemInstance[$storeId] = $this->createSearchResult($collection);
        }

        return $this->cacheItemInstance[$storeId];
    }

    /**
     * Get Items collection
     *
     * @param int $storeId
     * @param bool $onlyActive
     *
     * @return Collection
     */
    private function getCollection(int $storeId, bool $onlyActive): Collection
    {
        /** @var Collection $collection */
        $collection = $this->itemCollectionFactory->create();
        $collection
            ->addStoreFilter($storeId)
            ->setOrder(Item::PARENT_ID, 'ASC')
            ->setOrder(Item::PRIORITY, 'ASC');

        if ($onlyActive) {
            $collection->addActiveFilter();
        }

        return $collection;
    }

    /**
     * Create Search Result
     *
     * @param ResourceModel\Item\Collection $collection
     *
     * @return ItemSearchResultInterface
     */
    private function createSearchResult(Collection $collection): ItemSearchResultInterface
    {
        /** @var ItemSearchResultInterface $searchResult */
        $searchResult = $this->itemSearchResultsFactory->create();
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());

        return $searchResult;
    }
}
