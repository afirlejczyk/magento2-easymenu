<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model\Item\Command;

use AMF\EasyMenuApi\Api\Data\ItemInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;

/**
 * Save Menu Item data command (Service Provider Interface - SPI)
 *
 * Separate command interface to which Repository proxies initial Get call, could be considered as SPI - Interfaces
 * that you should extend and implement to customize current behaviour, but NOT expected to be used (called) in the code
 * of business logic directly
 *
 * @see \AMF\EasyMenuAPI\Api\ItemRepositoryInterface
 * @api
 */
interface SaveInterface
{
    /**
     * Save Menu Item data command
     *
     * @param ItemInterface $item
     *
     * @return int
     * @throws CouldNotSaveException
     * @throws LocalizedException
     */
    public function execute(ItemInterface $item): int;
}
