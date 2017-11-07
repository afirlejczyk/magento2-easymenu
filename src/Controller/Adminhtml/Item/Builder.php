<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Controller\Adminhtml\Item;

use AF\EasyMenu\Api\ItemRepositoryInterface;
use AF\EasyMenu\Controller\Adminhtml\RegistryConstants;
use AF\EasyMenu\Model\Item as MenuItem;
use AF\EasyMenu\Model\ItemFactory as ItemFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Builder
 */
class Builder
{

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var ItemFactory
     */
    private $itemFactory;

    /**
     * @var ItemRepositoryInterface
     */
    private $itemRepository;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Builder constructor.
     *
     * @param ItemFactory $menuFactory
     * @param Registry $coreRegistry
     * @param ItemRepositoryInterface $itemRepository
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ItemFactory $menuFactory,
        Registry $coreRegistry,
        ItemRepositoryInterface $itemRepository,
        StoreManagerInterface $storeManager
    ) {
        $this->itemFactory = $menuFactory;
        $this->registry = $coreRegistry;
        $this->itemRepository = $itemRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * Build Menu Item base on user request
     *
     * @param RequestInterface $request
     *
     * @return MenuItem
     */
    public function build(RequestInterface $request)
    {
        $menuItemId = (int) $request->getParam('item_id', 0);
        $storeId = (int) $request->getParam('store', $this->getDefaultStoreId());

        try {
            $menuItem = $this->itemRepository->getById($menuItemId);
            $storeId = $menuItem->getStoreId();
        } catch (NoSuchEntityException $e) {
            $parentId = (int) $request->getParam('parent_id', 0);

            /** @var MenuItem $menuItem */
            $menuItem = $this->itemFactory->create();
            $menuItem->setParentId($parentId);
        }

        $store = $this->storeManager->getStore($storeId);

        $this->registry->register(RegistryConstants::CURRENT_MENU_ITEM, $menuItem);
        $this->registry->register(RegistryConstants::CURRENT_STORE, $store);

        return $menuItem;
    }

    /**
     * @return integer
     */
    public function getDefaultStoreId()
    {
        return $this->storeManager->getDefaultStoreView()->getId();
    }
}
