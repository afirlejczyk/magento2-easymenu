<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\Ui\DataProvider\Item\Form\Modifier;

use AMF\EasyMenuAdminUi\Model\Locator\LocatorInterface;
use AMF\EasyMenuAdminUi\Ui\DataProvider\Item\ValueFieldLookup;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;

/**
 * Main Form Modifier
 */
class General implements ModifierInterface
{
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var ValueFieldLookup
     */
    private $valueLookup;

    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * General constructor.
     *
     * @param LocatorInterface $locator
     * @param DataPersistorInterface $dataPersistor
     * @param ValueFieldLookup $valueLookup
     */
    public function __construct(
        LocatorInterface $locator,
        DataPersistorInterface $dataPersistor,
        ValueFieldLookup $valueLookup
    ) {
        $this->locator = $locator;
        $this->dataPersistor = $dataPersistor;
        $this->valueLookup = $valueLookup;
    }

    /**
     * @param array $meta
     *
     * @return array
     */
    public function modifyMeta(array $meta)
    {
        return $meta;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function modifyData(array $data)
    {
        $persistentData = $this->dataPersistor->get('menu_item');

        if (is_array($persistentData) && count($persistentData)) {
            return $this->resolvePersistentData($data);
        }

        $menuItem = $this->locator->getMenuItem();
        $menuItem = $this->updateValue($menuItem);
        $data[$menuItem->getId()] = $menuItem->getData();

        return $data;
    }

    /**
     * @param ItemInterface $menuItem
     *
     * @return ItemInterface
     */
    private function updateValue(ItemInterface $menuItem): ItemInterface
    {
        $type = $menuItem->getTypeId();
        $valueFieldName = $this->valueLookup->getValueFieldNameByType($type);

        if ($valueFieldName !== '') {
            $menuItem->setData($valueFieldName, $menuItem->getValue());
        }

        return $menuItem;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function resolvePersistentData(array $data): array
    {
        /** @var ItemInterface $menuItem */
        $menuItem = $this->locator->getMenuItem();

        $persistentData = (array) $this->dataPersistor->get('menu_item');
        $this->dataPersistor->clear('menu_item');
        $data[$menuItem->getId()] = $persistentData;

        return $data;
    }
}
