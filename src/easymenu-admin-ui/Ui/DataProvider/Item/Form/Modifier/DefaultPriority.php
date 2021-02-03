<?php


namespace AMF\EasyMenuAdminUi\Ui\DataProvider\Item\Form\Modifier;

use AMF\EasyMenuAdminUi\Model\Locator\LocatorInterface;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use AMF\EasyMenuApi\Model\GetMaxPriorityInterface;

use Magento\Ui\DataProvider\Modifier\ModifierInterface;

/**
 * Default Priority Modifier
 */
class DefaultPriority implements ModifierInterface
{
    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * @var GetMaxPriorityInterface
     */
    private $getMaxPriority;

    /**
     * Priority constructor.
     *
     * @param LocatorInterface $locator
     * @param GetMaxPriorityInterface $itemResource
     */
    public function __construct(
        LocatorInterface $locator,
        GetMaxPriorityInterface $itemResource
    ) {
        $this->getMaxPriority = $itemResource;
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
        $priority = $this->getMaxPriority->execute($this->getStoreId(), $parent) + 1;
        $priorityFieldName = ItemInterface::PRIORITY;

        $meta['general']['children'][$priorityFieldName]['arguments']['data']['config']['default'] = $priority;

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
        return $this->locator->getMenuItem()->getParentId();
    }
}
