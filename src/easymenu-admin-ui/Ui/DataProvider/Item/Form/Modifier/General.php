<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\Ui\DataProvider\Item\Form\Modifier;

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
     * General constructor.
     *
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(DataPersistorInterface $dataPersistor)
    {
        $this->dataPersistor = $dataPersistor;
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

        return $data;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function resolvePersistentData(array $data): array
    {
        $persistentData = (array) $this->dataPersistor->get('menu_item');
        $itemId = $persistentData['item_id'];
        $data[$itemId] = $persistentData;

        $this->dataPersistor->clear('menu_item');

        return $data;
    }
}
