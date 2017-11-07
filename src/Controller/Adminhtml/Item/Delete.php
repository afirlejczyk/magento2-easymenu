<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Controller\Adminhtml\Item;

use AF\EasyMenu\Api\ItemRepositoryInterface;
use AF\EasyMenu\Model\Item as MenuItem;
use AF\EasyMenu\Model\ItemManagementInterface;
use AF\EasyMenu\Controller;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Delete
 */
class Delete extends Controller\Adminhtml\Item
{
    /**
     * @var ItemManagementInterface
     */
    private $itemManagement;

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
     * @param ItemManagementInterface $itemManagement
     */
    public function __construct(
        Action\Context $context,
        ItemRepositoryInterface $itemRepository,
        PageFactory $resultPageFactory,
        Builder $menuItemBuilder,
        \Psr\Log\LoggerInterface $logger,
        ItemManagementInterface $itemManagement
    ) {
        $this->itemManagement = $itemManagement;
        $this->logger = $logger;

        parent::__construct($context, $itemRepository, $resultPageFactory, $menuItemBuilder);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $menuItem = $this->getMenuItemBuilder()->build($this->getRequest());
        $parentId = $menuItem->getParentId();
        $storeId = $menuItem->getStoreId();

        if ($menuItem->getId()) {
            try {
                $children = $this->itemManagement->getChildren($menuItem->getId(), true);

                /** @var MenuItem $child */
                foreach ($children as $child) {
                    $this->updateChild($child, $parentId);
                }

                $this->getItemRepository()->delete($menuItem);
                $this->messageManager->addSuccessMessage(__('You deleted menu item.'));
            } catch (LocalizedException $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
                $this->logger->critical($exception);
            } catch (\Exception $exception) {
                $this->messageManager->addErrorMessage(__('Something went wrong while trying to delete menu item.'));
                $this->logger->critical($exception);
            }
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($parentId) {
            return $resultRedirect->setPath(
                'easymenu/*/edit',
                [
                    '_current' => false,
                    $this->getItemIdFieldName() => $parentId,
                ]
            );
        }

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
     * @param MenuItem $child
     * @param int $parentId
     *
     * @return void
     */
    private function updateChild(MenuItem $child, $parentId)
    {
        $child->setParentId($parentId);
        $this->getItemRepository()->save($child);
    }
}
