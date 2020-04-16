<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\Controller\Adminhtml\Item;

use AMF\EasyMenuAdminUi\Controller\Adminhtml\Item;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;

/**
 * Item edit controller
 */
class Edit extends Item implements HttpGetActionInterface
{
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $menuItem = $this->getItemBuilder()->build($this->getRequest());

        if (! $menuItem->getId()) {
            /** @var Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();

            return $resultRedirect->setPath(
                '*/*/add',
                [
                    '_current' => true,
                    'store' => $menuItem->getStoreId(),
                    $this->getItemIdFieldName() => null,
                ]
            );
        }

        return $this->createPage($menuItem->getName());
    }

    /**
     * Create Page
     *
     * @param string $itemTitle
     *
     * @return Page
     */
    private function createPage(string $itemTitle): Page
    {
        /** @var Page $resultPage */
        $resultPage = $this->createResultPage();
        $resultPage->getConfig()->getTitle()->prepend($itemTitle);

        return $resultPage;
    }
}
