<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\Controller\Adminhtml;

use AMF\EasyMenuAdminUi\Controller\Adminhtml\Item\Builder;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use AMF\EasyMenuApi\Api\ItemRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Item
 */
abstract class Item extends Action
{
    private const ADMIN_ITEM_RESOURCE = 'AMF_EasyMenuAdminUi::menu';

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
    private $itemBuilder;

    /**
     * @var AuthorizationInterface
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
        $this->itemBuilder = $menuItemBuilder;

        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->authorization->isAllowed(self::ADMIN_ITEM_RESOURCE);
    }

    /**
     * Retrieve Item Builder
     *
     * @return Builder
     */
    protected function getItemBuilder(): Builder
    {
        return $this->itemBuilder;
    }

    /**
     * Retrieve menu item id
     *
     * @return int
     */
    protected function getMenuItemId(): int
    {
        return (int) $this->getRequest()->getParam('item_id', 0);
    }

    /**
     * Retrieve Item Repository
     *
     * @return ItemRepositoryInterface
     */
    protected function getItemRepository(): ItemRepositoryInterface
    {
        return $this->itemRepository;
    }

    /**
     * Create result Page
     *
     * @return \Magento\Framework\View\Result\Page
     */
    protected function createResultPage(): \Magento\Framework\View\Result\Page
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu(self::ADMIN_ITEM_RESOURCE);
        $resultPage->getConfig()->getTitle()->prepend(__('Easy Menu'));

        return $resultPage;
    }

    /**
     * Retrieve item ID field name
     *
     * @return string
     */
    protected function getItemIdFieldName(): string
    {
        return ItemInterface::ITEM_ID;
    }
}
