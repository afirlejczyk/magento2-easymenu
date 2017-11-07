<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Model;

use AF\EasyMenu\Api;
use AF\EasyMenu\Api\Data;
use AF\EasyMenu\Model\ResourceModel\Item as ResourceItem;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Item Repository
 */
class ItemRepository implements Api\ItemRepositoryInterface
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
    public function save(Data\ItemInterface $item)
    {
        try {
            $this->resource->save($item);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $item;
    }

    /**
     * @inheritdoc
     */
    public function getById($itemId)
    {
        $item = $this->itemFactory->create();
        $this->resource->load($item, $itemId);

        if (!$item->getId()) {
            throw new NoSuchEntityException(__('Item Menu with id "%1" does not exist.', $itemId));
        }

        return $item;
    }

    /**
     * @inheritdoc
     */
    public function delete(Data\ItemInterface $item)
    {
        try {
            $this->resource->delete($item);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteById($itemId)
    {
        return $this->delete($this->getById($itemId));
    }
}
