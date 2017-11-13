<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Model\Item;

use AF\EasyMenu\Api\ItemRepositoryInterface;
use AF\EasyMenu\Model\Item;
use AF\EasyMenu\Model\ResourceModel\Item as ItemResourceModel;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Item Mover
 */
class Mover
{
    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    private $eventManager;

    /**
     * @var \Magento\Framework\App\CacheInterface
     */
    private $cacheManager;

    /**
     * @var ItemResourceModel
     */
    private $resourceModel;

    /**
     * MoveItem constructor.
     *
     * @param \Magento\Framework\Event\ManagerInterface $eventDispatcher
     * @param \Magento\Framework\App\CacheInterface $cacheManager
     * @param ItemResourceModel $itemResourceModel
     * @param ItemRepositoryInterface $itemRepository
     */
    public function __construct(
        \Magento\Framework\Event\ManagerInterface $eventDispatcher,
        \Magento\Framework\App\CacheInterface $cacheManager,
        ItemResourceModel $itemResourceModel,
        ItemRepositoryInterface $itemRepository
    ) {
        $this->itemRepository = $itemRepository;
        $this->resourceModel = $itemResourceModel;
        $this->cacheManager = $cacheManager;
        $this->eventManager = $eventDispatcher;
    }

    /**
     * @param Item $menuItem
     * @param int $parentId
     * @param int $afterMenuItemId
     *
     * @return $this
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function move(Item $menuItem, $parentId, $afterMenuItemId)
    {
        $parentId = (int) $parentId;

        if ($parentId) {
            try {
                $parent = $this->itemRepository->get($parentId);
            } catch (NoSuchEntityException $e) {
                throw new LocalizedException(
                    __(
                        'Sorry, but we can\'t find the new parent item you selected.'
                    ),
                    $e
                );
            }
        } else {
            $parent = null;
        }

        if (!$menuItem->getId()) {
            throw new LocalizedException(__('Sorry, but we can\'t find the new item you selected.'));
        } elseif ((null !== $parent) && $parent->getId() == $menuItem->getId()) {
            throw new LocalizedException(
                __('We can\'t move the menu item because the parent item name matches the child item name.')
            );
        }

        $this->resourceModel->beginTransaction();

        try {
            $this->resourceModel->changeParent($menuItem, $parent, $afterMenuItemId);
            $this->resourceModel->commit();
        } catch (\Exception $e) {
            $this->resourceModel->rollBack();
            throw $e;
        }

        $this->eventManager->dispatch('clean_cache_by_tags', ['object' => $menuItem]);

        $cacheTags = [
            Item::CACHE_TAG,
            Item::CACHE_TAG_STORE . $menuItem->getStoreId(),
        ];

        $this->cacheManager->clean($cacheTags);

        return $this;
    }
}
