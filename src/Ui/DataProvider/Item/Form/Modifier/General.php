<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Ui\DataProvider\Item\Form\Modifier;

use AF\EasyMenu\Model\Locator\LocatorInterface;
use AF\EasyMenu\Model\Item;
use AF\EasyMenu\Ui\DataProvider\Item\ValueLookup;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;

/**
 * Main Form Modifier
 */
class General implements ModifierInterface
{
    const KEY_SUBMIT_URL = 'submit_url';

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var ValueLookup
     */
    private $valueLookup;

    /**
     * @var \AF\EasyMenu\Model\Locator\LocatorInterface
     */
    private $locator;

    /**
     * General constructor.
     *
     * @param LocatorInterface $locator
     * @param DataPersistorInterface $dataPersistor
     * @param ValueLookup $valueLookup
     */
    public function __construct(
        LocatorInterface $locator,
        DataPersistorInterface $dataPersistor,
        ValueLookup $valueLookup
    ) {
        $this->locator = $locator;
        $this->dataPersistor = $dataPersistor;
        $this->valueLookup = $valueLookup;
    }

    /**
     * @inheritdoc
     */
    public function modifyData(array $data)
    {
        $persistentData = $this->dataPersistor->get('menu_item');

        if (!empty($persistentData)) {
            return $this->resolvePersistentData($data);
        }

        /** @var Item $menuItem */
        $menuItem = $this->locator->getMenuItem();

        if ($menuItem->getId()) {
            $type = (int) $menuItem->getType();
            $valueFieldName = $this->valueLookup->getValueFieldNameByType($type);

            if (!empty($valueFieldName)) {
                $menuItem->setData($valueFieldName, $menuItem->getValue());
            }

            $data[$menuItem->getId()] = $menuItem->getData();
        }

        return $data;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function resolvePersistentData(array $data)
    {
        /** @var Item $menuItem */
        $menuItem = $this->locator->getMenuItem();

        $persistentData = (array) $this->dataPersistor->get('menu_item');
        $this->dataPersistor->clear('menu_item');
        $data[$menuItem->getId()] = $persistentData;

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function modifyMeta(array $meta)
    {
        return $meta;
    }
}
