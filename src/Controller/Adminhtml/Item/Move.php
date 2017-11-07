<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Controller\Adminhtml\Item;

use AF\EasyMenu\Api\ItemRepositoryInterface;
use AF\EasyMenu\Model\Item\Mover as ItemMover;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Move
 */
class Move extends \AF\EasyMenu\Controller\Adminhtml\Item
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    private $layoutFactory;

    /**
     * @var \Psr\Log\LoggerInterface $logger
     */
    private $logger;

    /**
     * @var ItemMover
     */
    private $itemMover;

    /**
     * Move constructor.
     *
     * @param Action\Context $context
     * @param ItemRepositoryInterface $itemRepository
     * @param PageFactory $resultPageFactory
     * @param Builder $menuItemBuilder
     * @param ItemMover $itemMover
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     */
    public function __construct(
        Action\Context $context,
        ItemRepositoryInterface $itemRepository,
        PageFactory $resultPageFactory,
        Builder $menuItemBuilder,
        ItemMover $itemMover,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\View\LayoutFactory $layoutFactory
    ) {
        parent::__construct($context, $itemRepository, $resultPageFactory, $menuItemBuilder);

        $this->resultJsonFactory = $resultJsonFactory;
        $this->layoutFactory = $layoutFactory;
        $this->logger = $logger;
        $this->itemMover = $itemMover;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        /**
         * New parent page identifier
         */
        $parentNodeId = $this->getRequest()->getPost('pid', false);

        /**
         * Page id after which we have put our page
         */
        $prevNodeId = $this->getRequest()->getPost('aid', false);

        /** @var $block \Magento\Framework\View\Element\Messages */
        $block = $this->layoutFactory->create()->getMessagesBlock();
        $error = false;

        try {
            $menuItem = $this->getMenuItemBuilder()->build($this->getRequest());

            if (!$menuItem->getId()) {
                throw new \Exception(__('Menu item is not available.'));
            }

            $this->itemMover->move($menuItem, $parentNodeId, $prevNodeId);
        } catch (LocalizedException $exception) {
            $error = true;
            $this->messageManager->addErrorMessage($exception->getMessage());
            $this->logger->critical($exception);
        } catch (\Exception $exception) {
            $error = true;
            $this->messageManager->addErrorMessage(__('There was menu item move error.'));
            $this->logger->critical($exception);
        }

        if (!$error) {
            $this->messageManager->addSuccessMessage(__('You moved menu item.'));
        }

        $block->setMessages($this->messageManager->getMessages(true));

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();

        return $resultJson->setData(
            [
                'messages' => $block->getGroupedHtml(),
                'error' => $error,
            ]
        );
    }
}
