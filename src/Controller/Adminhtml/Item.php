<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Controller\Adminhtml;

use AF\EasyMenu\Api\ItemRepositoryInterface;
use AF\EasyMenu\Controller\Adminhtml\Item\Builder;
use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Item
 */
abstract class Item extends Action
{
    const ADMIN_RESOURCE = 'AF_EasyMenu::menu';

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @var ItemRepositoryInterface
     */
    private $itemRepository;

    /**
     * @var Builder
     */
    private $menuItemBuilder;

    /**
     * @var \Magento\Framework\AuthorizationInterface
     */
    private $authorization;

    /**
     * Item constructor.
     *
     * @param Action\Context $context
     * @param ItemRepositoryInterface $itemRepository
     * @param PageFactory $resultPageFactory
     * @param Builder $menuItemBuilder
     */
    public function __construct(
        Action\Context $context,
        ItemRepositoryInterface $itemRepository,
        PageFactory $resultPageFactory,
        Builder $menuItemBuilder
    ) {
        $this->authorization = $context->getAuthorization();
        $this->resultPageFactory = $resultPageFactory;
        $this->itemRepository = $itemRepository;
        $this->menuItemBuilder = $menuItemBuilder;

        parent::__construct($context);
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->authorization->isAllowed(static::ADMIN_RESOURCE);
    }

    /**
     * @return Builder
     */
    protected function getMenuItemBuilder()
    {
        return $this->menuItemBuilder;
    }

    /**
     * Retrieve menu item id
     *
     * @return int
     */
    protected function getMenuItemId()
    {
        return (int) $this->getRequest()->getParam('item_id', 0);
    }

    /**
     * @return ItemRepositoryInterface
     */
    protected function getItemRepository()
    {
        return $this->itemRepository;
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    protected function createResultPage()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE);
        $resultPage->getConfig()->getTitle()->prepend(__('Easy Menu'));

        return $resultPage;
    }

    /**
     * @return string
     */
    protected function getItemIdFieldName()
    {
        return \AF\EasyMenu\Api\Data\ItemInterface::ITEM_ID;
    }
}
