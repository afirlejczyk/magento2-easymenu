<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model\Item;

use AMF\EasyMenu\Model\Item;
use AMF\EasyMenu\Model\ResourceModel\Item\ChangeParent;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use AMF\EasyMenuApi\Api\ItemRepositoryInterface;
use AMF\EasyMenuApi\Model\ItemMoverInterface;
use Exception;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Item Mover
 */
class Mover implements ItemMoverInterface
{
    /**
     * @var ManagerInterface
     */
    private $eventManager;

    /**
     * @var CacheInterface
     */
    private $cacheManager;

    /**
     * @var ChangeParent
     */
    private $changeParent;

    /**
     * @var ItemRepositoryInterface
     */
    private $itemRepository;

    /**
     * MoveItem constructor.
     *
     * @param ManagerInterface $eventDispatcher
     * @param CacheInterface $cacheManager
     * @param ChangeParent $itemResourceModel
     * @param ItemRepositoryInterface $itemRepository
     */
    public function __construct(
        ManagerInterface $eventDispatcher,
        CacheInterface $cacheManager,
        ChangeParent $itemResourceModel,
        ItemRepositoryInterface $itemRepository
    ) {
        $this->itemRepository = $itemRepository;
        $this->changeParent = $itemResourceModel;
        $this->cacheManager = $cacheManager;
        $this->eventManager = $eventDispatcher;
    }

    /**
     * Move Item in menu tree
     *
     * @param ItemInterface $menuItem
     * @param int $parentId
     * @param int $afterMenuItemId
     *
     * @return void
     *
     * @throws Exception
     * @throws LocalizedException
     */
    public function move(ItemInterface $menuItem, int $parentId, int $afterMenuItemId): void
    {
        $parent = null;

        if ($parentId) {
            $parent = $this->getItemById((int) $parentId);
            $this->validateParentItem($menuItem, $parent);
        }

        $this->changeParent->execute($menuItem, $parent, $afterMenuItemId);
        $this->cleanCache($menuItem);
    }

    /**
     * Validate Parent Item
     *
     * @param ItemInterface $menuItem
     * @param ItemInterface|null $parent
     *
     * @return void
     *
     * @throws LocalizedException
     */
    private function validateParentItem(ItemInterface $menuItem, ItemInterface $parent): void
    {
        if ($parent->getId() === $menuItem->getId()) {
            throw new LocalizedException(
                __('We can\'t move the menu item because the parent item name matches the child item.')
            );
        }
    }

    /**
     * Get Item By Id
     *
     * @param int $itemId
     *
     * @return ItemInterface|null
     *
     * @throws LocalizedException
     */
    private function getItemById(int $itemId): ?ItemInterface
    {
        try {
            return $this->itemRepository->get($itemId);
        } catch (NoSuchEntityException $exception) {
            throw new LocalizedException(
                __(
                    'Sorry, but we can\'t find the new parent item you selected.'
                ),
                $exception
            );
        }
    }

    /**
     * Clean Cache
     *
     * @param ItemInterface $menuItem
     *
     * @return void
     */
    private function cleanCache(ItemInterface $menuItem): void
    {
        $cacheTags = [
            Item::CACHE_TAG,
            sprintf('%s_%s', Item::CACHE_TAG_STORE, $menuItem->getStoreId()),
        ];

        $this->eventManager->dispatch('clean_cache_by_tags', ['object' => $menuItem]);
        $this->cacheManager->clean($cacheTags);
    }
}
