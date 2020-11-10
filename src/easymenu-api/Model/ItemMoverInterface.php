<?php

declare(strict_types = 1);

namespace AMF\EasyMenuApi\Model;

use AMF\EasyMenuApi\Api\Data\ItemInterface;

/**
 * Move Item in hierarchy
 */
interface ItemMoverInterface
{
    /**
     * @param ItemInterface $menuItem
     * @param int|null $parentId
     * @param int|null $afterMenuItemId
     *
     * @return void
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function move(
        ItemInterface $menuItem,
        ?int $parentId,
        ?int $afterMenuItemId
    ): void;
}
