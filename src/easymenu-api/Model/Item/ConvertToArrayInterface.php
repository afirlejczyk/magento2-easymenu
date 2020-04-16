<?php

declare(strict_types=1);

namespace AMF\EasyMenuApi\Model\Item;

use AMF\EasyMenuApi\Api\Data\ItemInterface;

/**
 * Convert Menu Item to array
 */
interface ConvertToArrayInterface
{
    /**
     * @param ItemInterface $item
     *
     * @return array
     */
    public function execute(ItemInterface $item): array;
}
