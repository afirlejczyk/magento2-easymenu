<?php

declare(strict_types = 1);

namespace AMF\EasyMenuApi\Api\Data;

/**
 * Search results of Repository::getList method
 *
 * Used fully qualified namespaces in annotations for proper work of WebApi request parser
 *
 * @api
 */
interface ItemSearchResultInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get stocks list
     *
     * @return \AMF\EasyMenuApi\Api\Data\ItemInterface[]
     */
    public function getItems();

    /**
     * Set stocks list
     *
     * @param \AMF\EasyMenuApi\Api\Data\ItemInterface[] $items
     *
     * @return void
     */
    public function setItems(array $items);
}
