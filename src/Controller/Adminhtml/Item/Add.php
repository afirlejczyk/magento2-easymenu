<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Controller\Adminhtml\Item;

use AF\EasyMenu\Api\ItemRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Add
 */
class Add extends \AF\EasyMenu\Controller\Adminhtml\Item
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Add constructor.
     *
     * @param Action\Context $context
     * @param ItemRepositoryInterface $itemRepository
     * @param PageFactory $resultPageFactory
     * @param Builder $menuItemBuilder
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Action\Context $context,
        ItemRepositoryInterface $itemRepository,
        PageFactory $resultPageFactory,
        Builder $menuItemBuilder,
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;

        parent::__construct($context, $itemRepository, $resultPageFactory, $menuItemBuilder);
    }

    /**
     * @return \Magento\Framework\View\Result\Page|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $storeId = $this->getRequest()->getParam('store');

        if (!$storeId) {
            /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();

            return $resultRedirect->setPath(
                'easymenu/item/add',
                ['store' => $this->getDefaultStoreId()]
            );
        }

        $this->getMenuItemBuilder()->build($this->getRequest());

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->createResultPage();
        $resultPage->getConfig()->getTitle()->prepend(__('New menu item'));

        return $resultPage;
    }

    /**
     * @return integer
     */
    public function getDefaultStoreId()
    {
        return $this->storeManager->getDefaultStoreView()->getId();
    }
}
