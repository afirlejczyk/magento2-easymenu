<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Widget\Button;

/**
 * Add custom buttons on item edit view
 */
class Edit extends Template
{
    private const BASE_URL = 'easymenu/item/';

    /**
     * Retrieve add sub item button html
     *
     * @return string
     */
    public function getAddSubButtonHtml(): string
    {
        return $this->getChildHtml('add_sub_button');
    }

    /**
     * Retrieve Main Add button html
     *
     * @return string
     */
    public function getMainAddButtonHtml(): string
    {
        return $this->getChildHtml('add_main_button');
    }

    /**
     * Retrieve move item url
     *
     * @return string
     */
    public function getMoveUrl(): string
    {
        return $this->getUrl(self::BASE_URL . 'move');
    }

    /**
     * Retrieve item edit url
     *
     * @return string
     */
    public function getEditUrl(): string
    {
        return $this->getUrl(self::BASE_URL . 'edit', ['_query' => false]);
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareLayout()
    {
        $this->addMainAddButton();
        $this->addSubItemAddButton();

        return parent::_prepareLayout();
    }

    /**
     * Add Main Add button
     *
     * @return void
     */
    private function addMainAddButton(): void
    {
        $addUrl = $this->getAddUrl();
        $this->addChild(
            'add_main_button',
            Button::class,
            [
                'label' => __('Add Main Menu Item'),
                'onclick' => "addNew('" . $addUrl . "', true)",
                'class' => 'add',
                'id' => 'add_main_item_button',
            ]
        );
    }

    /**
     * Add sub item add button
     *
     * @return void
     */
    private function addSubItemAddButton(): void
    {
        $addUrl = $this->getAddUrl();
        $this->addChild(
            'add_sub_button',
            Button::class,
            [
                'label' => __('Add Sub Menu Item'),
                'onclick' => "addNew('" . $addUrl . "', false)",
                'class' => 'add',
                'id' => 'add_sub_item_button',
            ]
        );
    }

    /**
     * Retrieve Add Item Url
     *
     * @return string
     */
    private function getAddUrl(): string
    {
        return $this->getUrl(
            '*/*/add',
            [
                'item_id' => null,
                '_current' => false,
                '_query' => false,
            ]
        );
    }
}
