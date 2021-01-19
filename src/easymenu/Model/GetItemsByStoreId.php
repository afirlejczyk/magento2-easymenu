<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model;

use AMF\EasyMenu\Model\ResourceModel\Item\Collection;
use AMF\EasyMenu\Model\ResourceModel\Item\CollectionFactory;
use AMF\EasyMenuApi\Api\Data\ItemSearchResultInterface;
use AMF\EasyMenuApi\Model\GetItemsByStoreIdInterface;

/**
 * {@inheritDoc}
 */
class GetItemsByStoreId implements GetItemsByStoreIdInterface
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
     *
     * @return ItemSearchResultInterface
     */
    public function getActive(int $storeId): ItemSearchResultInterface
    {
        $cacheKey = $this->getCacheKey($storeId, true);

        if (! isset($this->cacheItemInstance[$cacheKey])) {
            $collection = $this->getCollection($storeId, true);
            $this->cacheItemInstance[$cacheKey] = $this->createSearchResult($collection);
        }

        return $this->cacheItemInstance[$cacheKey];
    }

    /**
     * @param int $storeId
     * @return ItemSearchResultInterface
     */
    public function getAll(int $storeId): ItemSearchResultInterface
    {
        $cacheKey = $this->getCacheKey($storeId, false);

        if (! isset($this->cacheItemInstance[$cacheKey])) {
            $collection = $this->getCollection($storeId, false);
            $this->cacheItemInstance[$cacheKey] = $this->createSearchResult($collection);
        }

        return $this->cacheItemInstance[$cacheKey];
    }

    /**
     * @param int $storeId
     * @param bool $onlyActive
     * @return string
     */
    private function getCacheKey(int $storeId, bool $onlyActive): string
    {
        return $storeId . '_' . (int)$onlyActive;
    }

    /**
     * Get Items collection
     *
     * @param int $storeId
     *
     * @return Collection
     */
    private function getCollection(int $storeId, bool $onlyActive): Collection
    {
        /** @var Collection $collection */
        $collection = $this->itemCollectionFactory->create();
        $collection->addStoreFilter($storeId);
        $collection->setOrder(Item::PARENT_ID, 'ASC');
        $collection->setOrder(Item::PRIORITY, 'ASC');

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
