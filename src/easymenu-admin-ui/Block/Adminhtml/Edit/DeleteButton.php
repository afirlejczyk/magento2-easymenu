<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\Block\Adminhtml\Edit;

use AMF\EasyMenuAdminUi\Model\Locator\LocatorInterface;
use Magento\Backend\Block\Template;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Represents delete button with pre-configured options
 * Provide an ability to show confirmation message on click on the "Delete" button
 */
class DeleteButton extends Template implements ButtonProviderInterface
{
    /**
     * @var LocatorInterface
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
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        $menuItem = $this->locator->getMenuItem();

        if ($menuItem->getId()) {
            $menuItemId = (int) $menuItem->getId();
            $deleteUrl = $this->getDeleteUrl($menuItemId);
            $deleteConfirmMsg = __('Are you sure you want to do this?');

            return [
                'id' => 'delete',
                'label' => __('Delete Item'),
                'on_click' => "deleteConfirm('" . $this->escapeJs($this->escapeHtml($deleteConfirmMsg)) .
                    "', '" . $deleteUrl . "')",
                'class' => 'delete',
                'sort_order' => 10,
            ];
        }

        return [];
    }

    /**
     * Retrieve delete url
     *
     * @param int $menuItemId
     *
     * @return string
     */
    public function getDeleteUrl(int $menuItemId): string
    {
        $params = ['id' => $menuItemId];
        $params = array_merge($params, $this->getDefaultUrlParams());

        return $this->getUrl('easymenu/item/delete', $params);
    }

    /**
     * Retrieve default params
     *
     * @return array
     */
    private function getDefaultUrlParams(): array
    {
        return [
            '_current' => true,
            '_query' => ['isAjax' => null],
        ];
    }
}
