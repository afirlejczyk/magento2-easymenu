<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\Controller\Adminhtml\Item;

use AMF\EasyMenuAdminUi\Controller\Adminhtml\Item;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use AMF\EasyMenuApi\Api\ItemRepositoryInterface;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;

/**
 * Item save controller
 */
class Save extends Item
{
    /**
     * @var LoggerInterface $logger
     */
    private $logger;

    /**
     * @var PostDataProcessor
     */
    private $dataProcessor;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * Save constructor.
     *
     * @param Action\Context $context
     * @param ItemRepositoryInterface $itemRepository
     * @param PageFactory $resultPageFactory
     * @param Builder $menuItemBuilder
     * @param DataPersistorInterface $dataPersistor
     * @param LoggerInterface $logger
     * @param PostDataProcessor $dataProcessor
     */
    public function __construct(
        Action\Context $context,
        ItemRepositoryInterface $itemRepository,
        PageFactory $resultPageFactory,
        Builder $menuItemBuilder,
        DataPersistorInterface $dataPersistor,
        LoggerInterface $logger,
        PostDataProcessor $dataProcessor
    ) {
        parent::__construct($context, $itemRepository, $resultPageFactory, $menuItemBuilder);

        $this->dataProcessor = $dataProcessor;
        $this->dataPersistor = $dataPersistor;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $menuItem = $this->getMenuItem();
        $this->save($menuItem);
        $this->dataPersistor->clear('menu_item');
        $data = $menuItem->getData();
        $data = $this->getRequest()->getPostValue();
        $this->dataPersistor->set('menu_item', $data);

        return $this->redirect($menuItem);
    }

    /**
     * Save Menu Item
     *
     * @param ItemInterface $menuItem
     *
     * @return void
     */
    private function save(ItemInterface $menuItem): void
    {
        try {
            $this->getItemRepository()->save($menuItem);
            $this->messageManager->addSuccessMessage(__('You saved menu item.'));
        } catch (LocalizedException $exception) {
            $this->logger->critical($exception);
            $this->messageManager->addErrorMessage($exception->getMessage());
        } catch (Exception $exception) {
            $this->logger->critical($exception);
            $this->messageManager->addErrorMessage(__('Something went wrong while saving the menu.'));
        }
    }

    /**
     * @param $menuItem
     *
     * @return Redirect
     */
    private function redirect(ItemInterface $menuItem): Redirect
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath(
            'easymenu/item/edit',
            [
                $this->getItemIdFieldName() => $menuItem->getId(),
                'store' => $menuItem->getStoreId(),
            ]
        );
    }

    /**
     * @return ItemInterface
     * @throws NoSuchStoreException
     */
    private function getMenuItem(): ItemInterface
    {
        $menuItem = $this->getItemBuilder()->build($this->getRequest());
        $data = $this->getRequest()->getPostValue();
        $data = $this->dataProcessor->process($data);
        $menuItem->setData($data);

        return $menuItem;
    }
}
