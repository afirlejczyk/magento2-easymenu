<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\Controller\Adminhtml\Item;

use AMF\EasyMenuAdminUi\Controller\Adminhtml\Item;
use AMF\EasyMenuApi\Model\ItemMoverInterface;
use AMF\EasyMenuApi\Api\ItemRepositoryInterface;
use Exception;
use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Element\Messages;
use Magento\Framework\View\LayoutFactory;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;

/**
 * Item move controller
 */
class Move extends Item implements HttpPostActionInterface
{
    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var LayoutFactory
     */
    private $layoutFactory;

    /**
     * @var LoggerInterface $logger
     */
    private $logger;

    /**
     * @var ItemMoverInterface
     */
    private $itemMover;

    /**
     * Move constructor.
     *
     * @param Action\Context $context
     * @param ItemRepositoryInterface $itemRepository
     * @param PageFactory $resultPageFactory
     * @param Builder $menuItemBuilder
     * @param ItemMoverInterface $itemMover
     * @param JsonFactory $resultJsonFactory
     * @param LoggerInterface $logger
     * @param LayoutFactory $layoutFactory
     */
    public function __construct(
        Action\Context $context,
        ItemRepositoryInterface $itemRepository,
        PageFactory $resultPageFactory,
        Builder $menuItemBuilder,
        ItemMoverInterface $itemMover,
        JsonFactory $resultJsonFactory,
        LoggerInterface $logger,
        LayoutFactory $layoutFactory
    ) {
        parent::__construct($context, $itemRepository, $resultPageFactory, $menuItemBuilder);

        $this->resultJsonFactory = $resultJsonFactory;
        $this->layoutFactory = $layoutFactory;
        $this->logger = $logger;
        $this->itemMover = $itemMover;
    }

    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        $error = $this->move();

        if (!$error) {
            $this->messageManager->addSuccessMessage(__('You moved menu item.'));
        }

        return $this->createResultJson($error);
    }

    /**
     * Create Json Result
     *
     * @param bool $error
     *
     * @return Json
     */
    private function createResultJson(bool $error): Json
    {
        $layout = $this->layoutFactory->create();
        $block = $layout->getMessagesBlock();

        $block->setMessages($this->messageManager->getMessages(true));

        $resultJson = $this->resultJsonFactory->create();

        $resultJson->setData(
            [
                'messages' => $block->getGroupedHtml(),
                'error' => $error,
            ]
        );

        return $resultJson;
    }

    /**
     * Move Item
     *
     * @return bool
     */
    private function move(): bool
    {
        $request = $this->getRequest();
        $parentNodeId = (int) $request->getParam('pid', false);
        $prevNodeId = (int) $request->getParam('aid', false);
        $error = false;

        try {
            $menuItem = $this->getItemBuilder()->build($this->getRequest());
            $this->itemMover->move($menuItem, $parentNodeId, $prevNodeId);
        } catch (Exception $exception) {
            $error = true;
            $this->messageManager->addErrorMessage(__('Error appear while moving item.'));
            $this->logger->critical($exception);
        }

        return $error;
    }
}
