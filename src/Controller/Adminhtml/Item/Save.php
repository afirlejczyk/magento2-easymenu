<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Controller\Adminhtml\Item;

use AF\EasyMenu\Api\ItemRepositoryInterface;
use AF\EasyMenu\Controller\Adminhtml\Item;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Save
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Save extends Item
{

    /**
     * @var \Psr\Log\LoggerInterface $logger
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
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Save constructor.
     *
     * @param Action\Context $context
     * @param ItemRepositoryInterface $itemRepository
     * @param PageFactory $resultPageFactory
     * @param Builder $menuItemBuilder
     * @param DataPersistorInterface $dataPersistor
     * @param \Psr\Log\LoggerInterface $logger
     * @param StoreManagerInterface $storeManager
     * @param PostDataProcessor $dataProcessor
     */
    public function __construct(
        Action\Context $context,
        ItemRepositoryInterface $itemRepository,
        PageFactory $resultPageFactory,
        Builder $menuItemBuilder,
        DataPersistorInterface $dataPersistor,
        \Psr\Log\LoggerInterface $logger,
        StoreManagerInterface $storeManager,
        PostDataProcessor $dataProcessor
    ) {
        parent::__construct($context, $itemRepository, $resultPageFactory, $menuItemBuilder);

        $this->dataProcessor = $dataProcessor;
        $this->dataPersistor = $dataPersistor;
        $this->logger = $logger;
        $this->storeManager = $storeManager;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $menuItem = $this->getMenuItemBuilder()->build($this->getRequest());
        $data = $this->getRequest()->getPostValue();
        $storeId = $this->getRequest()->getParam('store', $this->storeManager->getDefaultStoreView());

        if (!empty($data)) {
            $data = $this->dataProcessor->process($data);
            $menuItem->setData($data);

            if (!$menuItem->getId()) {
                $menuItem->setParentId($this->getParentId());
                $menuItem->setStore($storeId);
            }

            try {
                if ($menuItem->getId() && (int) $menuItem->getId() == (int) $menuItem->getParentId()) {
                    throw new LocalizedException(
                        __('You cannot select yourself as parent. Please select different parent item.')
                    );
                }

                $this->getItemRepository()->save($menuItem);
                $this->messageManager->addSuccessMessage(__('You saved menu item.'));
                $this->dataPersistor->clear('menu_item');

                $data = $menuItem->getData();
            } catch (AlreadyExistsException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->logger->critical($e);
                $this->messageManager->addErrorMessage(__('Something went wrong while saving the menu.'));
            }

            $this->dataPersistor->set('menu_item', $data);
        }

        return $resultRedirect->setPath(
            'easymenu/item/edit',
            [
                $this->getItemIdFieldName() => $menuItem->getId(),
                'store' => $storeId,
            ]
        );
    }

    /**
     * @return int
     */
    private function getParentId()
    {
        return (int) $this->getRequest()->getParam('parent_id', 0);
    }
}
