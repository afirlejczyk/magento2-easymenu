<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model\Item\Command;

use AMF\EasyMenuApi\Api\Data\ItemInterface;
use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * Delete Menu Item data
 *
 * @see \AMF\EasyMenuAPI\Api\ItemRepositoryInterface
 * @api
 */
interface DeleteInterface
{
    /**
     * Delete Menu Item
     *
     * @param ItemInterface $item
     *
     * @return void
     * @throws CouldNotDeleteException
     */
    public function execute(ItemInterface $item): void;
}
