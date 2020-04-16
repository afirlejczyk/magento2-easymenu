<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\Model\Locator;

use AMF\EasyMenuApi\Api\Data\ItemInterface;
use Magento\Store\Api\Data\StoreInterface;

/**
 * Responsible for providing currently selected item from menu and current store
 */
interface LocatorInterface
{
    /**
     * Retrieve MenuItem
     *
     * @return ItemInterface
     */
    public function getMenuItem(): ItemInterface;

    /**
     * Retrieve Store
     *
     * @return StoreInterface
     */
    public function getStore(): StoreInterface;
}
