<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model\Item\Command;

use AMF\EasyMenuApi\Api\Data\ItemInterface;
use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * Delete Menu Item data
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
interface DeleteInterface
{
    /**
     * Delete Menu Item
     *
     * @throws CouldNotDeleteException
     */
    public function execute(ItemInterface $item): void;
}
