<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model\Item;

use AMF\EasyMenu\Model\Item\UrlProvider\Pool;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use AMF\EasyMenuApi\Model\GetAllItemsInterface;

/**
 * @inheritdoc
 */
class UrlBuilder implements UrlBuilderInterface
{
    /**
     * @var Pool
     */
    private $pool;

    /**
     * @var GetAllItemsInterface
     */
    private $getAllItems;

    /**
     * @var array
     */
    private $urlsByType = [];

    /**
     * @var array
     */
    private $itemsByType = [];

    /**
     * UrlBuilder constructor.
     *
     * @param GetAllItemsInterface $itemManagement
     * @param Pool $pool
     */
    public function __construct(
        GetAllItemsInterface $itemManagement,
        Pool $pool
    ) {
        $this->getAllItems = $itemManagement;
        $this->pool = $pool;
    }

    /**
     * @param int $storeId
     *
     * @return array
     */
    public function getUrlsForActiveItems(int $storeId): array
    {
        $searchResult = $this->getAllItems->execute($storeId, true);
        $items = $searchResult->getItems();

        $this->groupItemsById(...$items);
        $this->loadUrls($storeId);

        $urls = [];

        foreach ($items as $item) {
            $urls[$item->getId()] = $this->urlsByType[$item->getTypeId()][$item->getId()] ?? '';
        }

        return $urls;
    }

    /**
     * Load Items Urls
     *
     * @param int $storeId
     */
    private function loadUrls(int $storeId): void
    {
        foreach ($this->itemsByType as $type => $items) {
            $urlProvider = $this->pool->get($type);
            $this->urlsByType[$type] = $urlProvider->loadAll($storeId, ...$items);
        }
    }

    /**
     * Group items by type
     *
     * @param ItemInterface ...$items
     *
     * @return void
     */
    private function groupItemsById(ItemInterface ...$items): void
    {
        $itemsIdByType = [];

        foreach ($items as $item) {
            $itemsIdByType[$item->getTypeId()][] = $item;
        }

        $this->itemsByType = $itemsIdByType;
    }
}
