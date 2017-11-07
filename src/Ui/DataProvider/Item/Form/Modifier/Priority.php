<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Ui\DataProvider\Item\Form\Modifier;

use AF\EasyMenu\Model\Item;
use AF\EasyMenu\Model\Locator\LocatorInterface;
use AF\EasyMenu\Model\ResourceModel\Item as ItemResource;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;

/**
 * Priority Modifier
 */
class Priority implements ModifierInterface
{

    /**
     * @var \AF\EasyMenu\Model\Locator\LocatorInterface
     */
    private $locator;

    /**
     * @var ArrayManager
     */
    private $arrayManager;

    /**
     * @var ItemResource
     */
    private $itemResource;

    /**
     * Priority constructor.
     *
     * @param ArrayManager $arrayManager
     * @param LocatorInterface $locator
     * @param ItemResource $itemResource
     */
    public function __construct(
        ArrayManager $arrayManager,
        LocatorInterface $locator,
        ItemResource $itemResource
    ) {
        $this->arrayManager = $arrayManager;
        $this->itemResource = $itemResource;
        $this->locator = $locator;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * @param array $meta
     *
     * @return array
     */
    public function modifyMeta(array $meta)
    {
        $meta = $this->customizePriorityField($meta);

        return $meta;
    }

    /**
     * @param array $meta
     *
     * @return array
     */
    private function customizePriorityField(array $meta)
    {
        $parent = $this->getParentId();
        $priority = $this->itemResource->getLastPriorityBaseOnParentId($this->getStoreId(), $parent);
        $priorityFieldName = Item::PRIORITY;

        $meta['general']['children'][$priorityFieldName]['arguments']['data']['config'] = ['default' => $priority];

        return $meta;
    }

    /**
     * @return int
     */
    private function getStoreId()
    {
        return $this->locator->getStore()->getId();
    }

    /**
     * Return Parent id from request
     *
     * @return int
     */
    private function getParentId()
    {
        return (int) $this->locator->getMenuItem()->getParentId();
    }
}
