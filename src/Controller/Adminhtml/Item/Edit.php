<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Controller\Adminhtml\Item;

/**
 * Class Edit
 */
class Edit extends \AF\EasyMenu\Controller\Adminhtml\Item
{

    /**
     * @return \Magento\Framework\Controller\Result\Redirect|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $menuItem = $this->getMenuItemBuilder()->build($this->getRequest());

        if (!$menuItem->getId()) {
            /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
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

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->createResultPage();
        $resultPage->getConfig()->getTitle()->prepend($menuItem->getName());

        return $resultPage;
    }
}
