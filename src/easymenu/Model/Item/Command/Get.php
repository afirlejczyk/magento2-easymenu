<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model\Item\Command;

use AMF\EasyMenu\Model\ItemFactory;
use AMF\EasyMenu\Model\ResourceModel\Item as ResourceItem;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * @inheritdoc
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

        if (! $item->getId()) {
            throw new NoSuchEntityException(__('Item Menu with id "%1" does not exist.', $itemId));
        }

        return $item;
    }
}
