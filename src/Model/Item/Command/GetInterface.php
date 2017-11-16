<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Model\Item\Command;

use AF\EasyMenu\Api\Data\ItemInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Get Menu Item by itemId command (Service Provider Interface - SPI)
 *
 * Separate command interface to which Repository proxies initial Get call, could be considered as SPI - Interfaces
 * that you should extend and implement to customize current behaviour, but NOT expected to be used (called) in the code
 * of business logic directly
 *
 * @see \AF\EasyMenu\Api\ItemRepositoryInterface
 * @api
 */
interface GetInterface
{
    /**
     * @param int $itemId
     *
     * @return ItemInterface
     * @throws NoSuchEntityException
     */
    public function execute(int $itemId): ItemInterface;
}
