<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model\Item\Command;

/**
 * Get Menu Items command (Service Provider Interface - SPI)
 *
 * Separate command interface to which Repository proxies initial
 * Get call, could be considered as SPI - Interfaces
 * that you should extend and implement to customize current
 * behaviour, but NOT expected to be used (called) in the code
 * of business logic directly
 *
 * @see \AMF\EasyMenuAPI\Api\ItemRepositoryInterface
 *
 * @api
 */
interface GetListInterface
{
    /**
     * Get Menu Items command
     */
    public function execute(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    ): \AMF\EasyMenuApi\Api\Data\ItemSearchResultInterface;
}
