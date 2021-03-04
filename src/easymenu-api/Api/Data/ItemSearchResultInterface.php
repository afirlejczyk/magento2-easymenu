<?php

declare(strict_types=1);

namespace AMF\EasyMenuApi\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Search results of Repository::getList method
 *
 * Used fully qualified namespaces in annotations for proper work of WebApi request parser
 *
 * @api
 */
interface ItemSearchResultInterface extends SearchResultsInterface
{
    /**
     * Get items list
     *
     * @return \AMF\EasyMenuApi\Api\Data\ItemInterface[]
     */
    public function getItems();

    /**
     * Set items list
     *
     * @param \AMF\EasyMenuApi\Api\Data\ItemInterface[] $items
     *
     * @return void
     */
    public function setItems(array $items);
}
