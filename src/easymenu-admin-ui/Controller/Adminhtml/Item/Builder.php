<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\Controller\Adminhtml\Item;

use AMF\EasyMenuAdminUi\Registry\CurrentItem as ItemRegistry;
use AMF\EasyMenuAdminUi\Registry\CurrentStore as StoreRegistry;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use AMF\EasyMenuApi\Api\Data\ItemInterfaceFactory;
use AMF\EasyMenuApi\Api\ItemRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Build a menu item based on a request
 */
class Builder
{
    /**
     * @var ItemRegistry
     */
    private $itemRegistry;

    /**
     * @var ItemInterfaceFactory
     */
    private $itemFactory;

    /**
     * @var ItemRepositoryInterface
     */
    private $itemRepository;

    /**
     * @var StoreRegistry
     */
    private $storeRegistry;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Builder constructor.
     *
     * @param ItemInterfaceFactory $menuFactory
     * @param ItemRegistry $coreRegistry
     * @param StoreRegistry $storeRegistry
     * @param ItemRepositoryInterface $itemRepository
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ItemInterfaceFactory $menuFactory,
        ItemRegistry $coreRegistry,
        StoreRegistry $storeRegistry,
        ItemRepositoryInterface $itemRepository,
        StoreManagerInterface $storeManager
    ) {
        $this->itemFactory = $menuFactory;
        $this->itemRegistry = $coreRegistry;
        $this->storeRegistry = $storeRegistry;
        $this->itemRepository = $itemRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * Build Menu Item base on user request
     *
     * @param RequestInterface $request
     *
     * @return ItemInterface
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function build(RequestInterface $request): ItemInterface
    {
        $menuItem = $this->getMenuItem($request);
        $store = $this->storeManager->getStore($menuItem->getStoreId());

        $this->itemRegistry->set($menuItem);
        $this->storeRegistry->set($store);

        return $menuItem;
    }

    /**
     * Retrieve Menu Item
     *
     * @param RequestInterface $request
     *
     * @return ItemInterface
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getMenuItem(RequestInterface $request): ItemInterface
    {
        $menuItemId = (int) $request->getParam('item_id');

        try {
            $menuItem = $this->itemRepository->get($menuItemId);
        } catch (\Exception $exception) {
            $menuItem = $this->createEmptyMenuItem($request);
        }

        return $menuItem;
    }

    /**
     * Retrieve Store
     *
     * @param RequestInterface $request
     *
     * @return \Magento\Store\Api\Data\StoreInterface
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getStore(RequestInterface $request): StoreInterface
    {
        $defaultStoreId = $this->getDefaultStoreId();
        $storeId = (int) $request->getParam('store', $defaultStoreId);

        return $this->storeManager->getStore($storeId);
    }

    /**
     * Retrieve default store ID
     *
     * @return int
     */
    private function getDefaultStoreId(): int
    {
        return (int) $this->storeManager->getDefaultStoreView()->getId();
    }

    /**
     * Create empty menu item
     *
     * @param RequestInterface $request
     *
     * @return ItemInterface
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function createEmptyMenuItem(
        RequestInterface $request
    ): ItemInterface {
        $parentId = (int) $request->getParam('parent_id');
        $store = $this->getStore($request);

        /** @var ItemInterface $menuItem */
        $menuItem = $this->itemFactory->create();
        $menuItem->setParentId($parentId);
        $menuItem->setStore((int) $store->getId());

        return $menuItem;
    }
}
