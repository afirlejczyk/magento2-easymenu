<?php
/**
 * @package  AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace AF\EasyMenu\Model\Item\Command;

use AF\EasyMenu\Api\Data\ItemInterface;
use AF\EasyMenu\Model\ResourceModel\Item as ResourceItem;
use AF\EasyMenu\Model\ItemFactory;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Get Menu Item by itemId command (Service Provider Interface - SPI)
 */
class Get implements GetInterface
{

    /**
     * @var ResourceItem
     */
    private $resource;

    /**
     * @var ItemFactory
     */
    private $itemFactory;

    /**
     * ItemRepository constructor.
     *
     * @param ResourceItem $resourceItem
     * @param ItemFactory $itemFactory
     */
    public function __construct(
        ResourceItem $resourceItem,
        ItemFactory $itemFactory
    ) {
        $this->resource = $resourceItem;
        $this->itemFactory = $itemFactory;
    }

    /**
     * @inheritdoc
     */
    public function execute(int $itemId): ItemInterface
    {
        $item = $this->itemFactory->create();
        $this->resource->load($item, $itemId);

        if (!$item->getId()) {
            throw new NoSuchEntityException(__('Item Menu with id "%1" does not exist.', $itemId));
        }

        return $item;
    }
}
