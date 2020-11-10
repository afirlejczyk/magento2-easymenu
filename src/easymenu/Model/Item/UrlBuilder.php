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
     * @var int
     */
    private $storeId;

    /**
     * UrlBuilder constructor.
     *
     * @param GetAllItemsInterface $itemManagement
     * @param Pool $pool
     * @param int $storeId
     */
    public function __construct(
        GetAllItemsInterface $itemManagement,
        Pool $pool,
        int $storeId
    ) {
        $this->getAllItems = $itemManagement;
        $this->pool = $pool;
        $this->storeId = $storeId;
    }

    /**
     * Retrieve urls for active menu items, result is group by item id
     *
     * @return array
     */
    public function getUrlsForActiveItems(): array
    {
        $searchResult = $this->getAllItems->execute($this->storeId, true);
        $items = $searchResult->getItems();

        $this->groupItemsById(...$items);
        $this->loadUrls();

        $urlByItemId = [];

        foreach ($items as $item) {
            $urlByItemId[$item->getId()] = $this->urlsByType[$item->getTypeId()][$item->getId()] ?? '';
        }

        return $urlByItemId;
    }

    /**
     * Load Items Urls
     */
    private function loadUrls(): void
    {
        foreach ($this->itemsByType as $type => $items) {
            $urlProvider = $this->pool->get($type);
            $this->urlsByType[$type] = $urlProvider->loadAll($this->storeId, ...$items);
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
