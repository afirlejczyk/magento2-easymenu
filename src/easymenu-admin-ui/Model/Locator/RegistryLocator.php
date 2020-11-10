<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\Model\Locator;

use AMF\EasyMenuAdminUi\Registry\CurrentItem as ItemRegistry;
use AMF\EasyMenuAdminUi\Registry\CurrentStore as StoreRegistry;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Store\Api\Data\StoreInterface;

/**
 * @inheritdoc
 */
class RegistryLocator implements LocatorInterface
{
    /**
     * @var ItemRegistry
     */
    private $itemRegistry;

    /**
     * @var StoreRegistry
     */
    private $storeRegistry;

    /**
     * RegistryLocator constructor.
     *
     * @param StoreRegistry $storeRegistry
     * @param ItemRegistry $registry
     */
    public function __construct(
        StoreRegistry $storeRegistry,
        ItemRegistry $registry
    ) {
        $this->itemRegistry = $registry;
        $this->storeRegistry = $storeRegistry;
    }

    /**
     * Retrieve Item from registry
     *
     * @return ItemInterface
     *
     * @throws NotFoundException
     */
    public function getMenuItem(): ItemInterface
    {
        $menuItem = $this->itemRegistry->get();

        return $menuItem ?? throw new NotFoundException(__('Menu Item was not registered'));
    }

    /**
     * Retrieve Store from registry
     *
     * @return StoreInterface
     *
     * @throws NotFoundException
     */
    public function getStore(): StoreInterface
    {
        $store = $this->storeRegistry->get();

        return $store ?? throw new NotFoundException(__('Store was not registered'));
    }
}
