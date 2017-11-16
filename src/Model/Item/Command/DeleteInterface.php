<?php
/**
 * @package  AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace AF\EasyMenu\Model\Item\Command;

use AF\EasyMenu\Api\Data\ItemInterface;
use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * Delete Menu Item data
 *
 * @see \AF\EasyMenu\Api\ItemRepositoryInterface
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
    public function execute(ItemInterface $item);
}
