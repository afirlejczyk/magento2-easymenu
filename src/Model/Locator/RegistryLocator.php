<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Model\Locator;

use AF\EasyMenu\Api\Data\ItemInterface;
use AF\EasyMenu\Controller\Adminhtml\RegistryConstants;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Registry;
use Magento\Store\Api\Data\StoreInterface;

/**
 * Class RegistryLocator
 */
class RegistryLocator implements LocatorInterface
{

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var ItemInterface
     */
    private $menuItem;

    /**
     * @var StoreInterface
     */
    private $store;

    /**
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @inheritdoc
     * @throws NotFoundException
     */
    public function getMenuItem()
    {
        if (null !== $this->menuItem) {
            return $this->menuItem;
        }

        $menuItem = $this->registry->registry(RegistryConstants::CURRENT_MENU_ITEM);

        if ($menuItem) {
            $this->menuItem = $menuItem;

            return $this->menuItem;
        }

        throw new NotFoundException(__('Menu Item was not registered'));
    }

    /**
     * @inheritdoc
     * @throws NotFoundException
     */
    public function getStore()
    {
        if (null !== $this->store) {
            return $this->store;
        }

        $store = $this->registry->registry(RegistryConstants::CURRENT_STORE);

        if ($store) {
            $this->store = $store;

            return $this->store;
        }

        throw new NotFoundException(__('Store was not registered'));
    }
}
