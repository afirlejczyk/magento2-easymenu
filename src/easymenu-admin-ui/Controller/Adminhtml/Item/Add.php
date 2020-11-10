<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\Controller\Adminhtml\Item;

use AMF\EasyMenuAdminUi\Controller\Adminhtml\Item;
use AMF\EasyMenuApi\Api\ItemRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Item add controller
 */
class Add extends Item implements HttpGetActionInterface
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

        parent::__construct(
            $context,
            $itemRepository,
            $resultPageFactory,
            $menuItemBuilder
        );
    }

    /**
     * @return Page|Redirect
     */
    public function execute()
    {
        $storeId = $this->getRequest()->getParam('store');

        if (! $storeId) {
            return $this->redirect();
        }

        $this->getItemBuilder()->build($this->getRequest());

        $resultPage = $this->createResultPage();
        $resultPage->getConfig()->getTitle()->prepend(__('New menu item'));

        return $resultPage;
    }

    /**
     * Redirect user
     *
     * @return Redirect
     */
    private function redirect()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath(
            'easymenu/item/add',
            ['store' => $this->storeManager->getDefaultStoreView()->getId()]
        );
    }
}
