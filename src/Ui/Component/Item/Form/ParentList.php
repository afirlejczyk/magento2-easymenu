<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Ui\Component\Item\Form;

use AF\EasyMenu\Model\Locator\LocatorInterface;
use AF\EasyMenu\Model\ResourceModel\Item\Collection as ItemCollection;
use AF\EasyMenu\Model\ResourceModel\Item\CollectionFactory as ItemCollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Options for PatenList
 */
class ParentList implements OptionSourceInterface
{

    /**
     * @var ItemCollectionFactory
     */
    private $itemCollectionFactory;

    /**
     * @var \AF\EasyMenu\Model\Locator\LocatorInterface
     */
    private $locator;

    /**
     * ParentList constructor.
     *
     * @param LocatorInterface $locator
     * @param ItemCollectionFactory $collectionFactory
     */
    public function __construct(
        LocatorInterface $locator,
        ItemCollectionFactory $collectionFactory
    ) {
        $this->locator = $locator;
        $this->itemCollectionFactory = $collectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return $this->getMenuItems();
    }

    /**
     * @return array
     */
    private function getMenuItems()
    {
        $storeId = $this->locator->getStore()->getId();

        /** @var ItemCollection $collection */
        $collection = $this->itemCollectionFactory->create();
        $collection->addStoreFilter($storeId);

        $menuItemsById = [
            0 => [
                'value' => 0,
                'label' => __('-- None --'),
                'is_active' => 1,
                'optgroup' => [],
            ],
        ];

        /** @var \AF\EasyMenu\Model\Item $item */
        foreach ($collection as $item) {
            $itemIds = [
                $item->getId(),
                $item->getParentId(),
            ];

            foreach ($itemIds as $itemId) {
                if (!isset($menuItemsById[$itemId])) {
                    $menuItemsById[$itemId] = ['value' => $itemId];
                }
            }

            $menuItemsById[$item->getId()]['is_active'] = 1;
            $menuItemsById[$item->getId()]['label'] = $item->getName();
            $menuItemsById[$item->getParentId()]['optgroup'][] = &$menuItemsById[$item->getId()];
        }

        if (count($menuItemsById) === 1) {
            return [];
        }

        return [0 => $menuItemsById[0]];
    }
}
