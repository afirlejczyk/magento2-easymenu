<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */
namespace AF\EasyMenu\Api;

/**
 * Interface ItemRepositoryInterface
 */
interface ItemRepositoryInterface
{
    /**
     * Save item.
     *
     * @param \AF\EasyMenu\Api\Data\ItemInterface $item
     *
     * @return \Magento\Cms\Api\Data\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(Data\ItemInterface $item);

    /**
     * Retrieve item.
     *
     * @param int $itemId
     *
     * @return \AF\EasyMenu\Api\Data\ItemInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($itemId);

    /**
     * Delete menu item.
     *
     * @param \AF\EasyMenu\Api\Data\ItemInterface $item
     *
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(Data\ItemInterface $item);

    /**
     * Delete block by ID.
     *
     * @param int $itemId
     *
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($itemId);
}
