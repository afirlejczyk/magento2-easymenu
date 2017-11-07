<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Block\Adminhtml\Edit;

use AF\EasyMenu\Model\Locator\LocatorInterface;
use Magento\Backend\Block\Template;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Delete Button
 */
class DeleteButton extends Template implements ButtonProviderInterface
{

    /**
     * @var \AF\EasyMenu\Model\Locator\LocatorInterface
     */
    private $locator;

    /**
     * DeleteButton constructor.
     *
     * @param Template\Context $context
     * @param LocatorInterface $locator
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        LocatorInterface $locator,
        array $data = []
    ) {
        $this->locator = $locator;

        parent::__construct($context, $data);
    }

    /**
     * @return array
     */
    public function getButtonData()
    {
        $menuItem = $this->locator->getMenuItem();

        if ($menuItem->getId()) {
            $menuItemId = $menuItem->getId();
            $deleteUrl = $this->getDeleteUrl(['id' => $menuItemId]);
            $deleteConfirmMsg = __("Are you sure you want to do this?");

            return [
                'id' => 'delete',
                'label' => __('Delete Item'),
                'on_click' =>
                    "deleteConfirm('" . $this->escapeJs($this->escapeHtml($deleteConfirmMsg)) .
                    "', '" . $deleteUrl . "')",
                'class' => 'delete',
                'sort_order' => 10,
            ];
        }

        return [];
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public function getDeleteUrl(array $params)
    {
        $params = array_merge($params, $this->getDefaultUrlParams());

        return $this->getUrl('easymenu/item/delete', $params);
    }

    /**
     * @return array
     */
    private function getDefaultUrlParams()
    {
        return [
            '_current' => true,
            '_query' => ['isAjax' => null],
        ];
    }
}
