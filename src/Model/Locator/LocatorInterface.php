<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Model\Locator;

use AF\EasyMenu\Api\Data\ItemInterface;
use Magento\Store\Api\Data\StoreInterface;

/**
 * Interface LocatorInterface
 */
interface LocatorInterface
{
    /**
     * @return ItemInterface
     */
    public function getMenuItem();

    /**
     * @return StoreInterface
     */
    public function getStore();
}
