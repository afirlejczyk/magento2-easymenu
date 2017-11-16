<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */
namespace AF\EasyMenu\Api;

use AF\EasyMenu\Api\Data\ItemInterface;

/**
 * Interface ItemRepositoryInterface
 */
interface ItemRepositoryInterface
{
    /**
     * Save menu item data
     *
     * @param \AF\EasyMenu\Api\Data\ItemInterface $item
     * @return int
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(Data\ItemInterface $item): int;

    /**
     * Retrieve menu item
     *
     * @param int $itemId
     *
     * @return \AF\EasyMenu\Api\Data\ItemInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($itemId): ItemInterface;

    /**
     * Delete menu item
     *
     * @param \AF\EasyMenu\Api\Data\ItemInterface $item
     *
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(Data\ItemInterface $item);
}
