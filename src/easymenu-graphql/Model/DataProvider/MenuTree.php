<?php

namespace AMF\EasyMenuGraphql\Model\DataProvider;

use AMF\EasyMenuApi\Api\Data\ItemInterface;
use AMF\EasyMenuApi\Model\GetItemsByStoreIdInterface;

/**
 * EasyMenu Tree data provider
 */
class MenuTree
{
    /**
     * @var GetItemsByStoreIdInterface
     */
    private $getItemsByStoreId;

    /**
     * MenuTree constructor.
     * @param GetItemsByStoreIdInterface $getAllItems
     */
    public function __construct(GetItemsByStoreIdInterface $getAllItems)
    {
        $this->getItemsByStoreId = $getAllItems;
    }

    /**
     * @param int $storeId
     * @return array
     */
    public function getDataByStoreId(int $storeId): array
    {
        $easyMenu = $this->getItemsByStoreId->getActive($storeId);

        return $this->buildMenuTree($easyMenu->getItems());
    }

    /**
     * @param array $items Items are sorted by parent id
     * @return array
     */
    private function buildMenuTree(array $items): array
    {
        $treeStructure = $tmpTreeStructure = [];

        foreach ($items as $item) {
            $item = $this->convertItemData($item);
            $parentId = $item[ItemInterface::PARENT_ID];
            $id = $item['id'];

            if ($parentId == 0) {
                $tmpTreeStructure[$id] = $item;
                $treeStructure[$id] = &$tmpTreeStructure[$id];
            } elseif (!empty($treeStructure[$parentId])) {
                $treeStructure[$parentId]['items'][$id] = $item;
            }
        }


        return $treeStructure;
    }

    /**
     * Convert ItemInterface object to array
     *
     * @param ItemInterface $menuItem
     * @return array
     */
    private function convertItemData(ItemInterface $menuItem): array
    {
        return [
            'id' => $menuItem->getId(),
            ItemInterface::TYPE => $menuItem->getTypeId(),
            ItemInterface::NAME => $menuItem->getName(),
            ItemInterface::VALUE => $menuItem->getValue(),
            ItemInterface::PARENT_ID => $menuItem->getParentId(),
            ItemInterface::PRIORITY => $menuItem->getPriority(),
            ItemInterface::STORE_ID => $menuItem->getStoreId(),
            ItemInterface::IS_ACTIVE => $menuItem->isActive(),
        ];
    }
}
