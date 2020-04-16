<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\Registry;

use AMF\EasyMenuApi\Api\Data\ItemInterface;

/**
 * Responsible for register and retrieve current item
 */
class CurrentItem
{
    /**
     * @var ItemInterface
     */
    private $item;

    /**
     * Set Currently Selected Item
     *
     * @param ItemInterface $item
     */
    public function set(ItemInterface $item): void
    {
        $this->item = $item;
    }

    /**
     * Get Current Item
     *
     * @return ItemInterface|null
     */
    public function get(): ?ItemInterface
    {
        return $this->item;
    }
}
