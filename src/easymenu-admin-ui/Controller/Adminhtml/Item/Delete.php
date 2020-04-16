<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\Controller\Adminhtml\Item;

use AMF\EasyMenuAdminUi\Controller;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use AMF\EasyMenuApi\Api\ItemRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\View\Result\PageFactory;

/**
 * Item delete controller
 */
class Delete extends Controller\Adminhtml\Item implements HttpPostActionInterface
{
    /**
     * @var \Psr\Log\LoggerInterface $logger
     */
    private $logger;

    /**
     * Delete constructor.
     *
     * @param Action\Context $context
     * @param ItemRepositoryInterface $itemRepository
     * @param PageFactory $resultPageFactory
     * @param Builder $menuItemBuilder
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        Action\Context $context,
        ItemRepositoryInterface $itemRepository,
        PageFactory $resultPageFactory,
        Builder $menuItemBuilder,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->logger = $logger;

        parent::__construct(
            $context,
            $itemRepository,
            $resultPageFactory,
            $menuItemBuilder
        );
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $menuItem = $this->getItemBuilder()->build($this->getRequest());

        if ($menuItem->getId()) {
            $this->deleteItem($menuItem);
        }

        return $this->redirect($menuItem);
    }

    /**
     * Delete item
     *
     * @param ItemInterface $menuItem
     */
    private function deleteItem(ItemInterface $menuItem): void
    {
        try {
            $this->getItemRepository()->delete($menuItem);
            $this->messageManager->addSuccessMessage(__('You deleted menu item.'));
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage(__('Something went wrong while trying to delete menu item.'));
            $this->logger->critical($exception);
        }
    }

    /**
     * Redirect user
     *
     * @param ItemInterface $menuItem
     *
     * @return Redirect
     */
    private function redirect(ItemInterface $menuItem): Redirect
    {
        $parentId = $menuItem->getParentId();
        $storeId = $menuItem->getStoreId();

        if ($parentId) {
            return $this->redirectToEditView($parentId);
        }

        return $this->redirectToAddNewView($storeId);
    }

    /**
     * Redirect user to Add New Item View
     *
     * @param int $storeId
     *
     * @return Redirect
     */
    private function redirectToAddNewView(int $storeId): Redirect
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath(
            '*/*/add',
            [
                '_current' => false,
                'store' => $storeId,
                $this->getItemIdFieldName() => null,
            ]
        );
    }

    /**
     * Redirect user to Edit View
     *
     * @param int $parentId
     *
     * @return Redirect
     */
    private function redirectToEditView(int $parentId): Redirect
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath(
            'easymenu/*/edit',
            [
                '_current' => false,
                $this->getItemIdFieldName() => $parentId,
            ]
        );
    }
}
