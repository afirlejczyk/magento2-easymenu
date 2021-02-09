<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\Ui\DataProvider\Item\Form;

use AMF\EasyMenuAdminUi\Model\Locator\LocatorInterface;
use AMF\EasyMenuAdminUi\Ui\DataProvider\Item\ValueFieldLookup;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use Magento\Framework\Api\Filter;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Ui\DataProvider\Modifier\PoolInterface;

/**
 * Form Item DataProvider
 */
class ItemDataProvider extends AbstractDataProvider
{
    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * @var PoolInterface
     */
    private $pool;

    /**
     * @var ValueFieldLookup
     */
    private $valueLookup;

    /**
     * ItemDataProvider constructor.
     * @param PoolInterface $pool
     * @param LocatorInterface $locator
     * @param ValueFieldLookup $valueLookup
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        PoolInterface $pool,
        LocatorInterface $locator,
        ValueFieldLookup $valueLookup,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {

        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data
        );

        $this->locator = $locator;
        $this->valueLookup = $valueLookup;
        $this->pool = $pool;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $data = [];
        $menuItem = $this->locator->getMenuItem();
        $data[$menuItem->getId()] = $this->convertToArray($menuItem);

        foreach ($this->pool->getModifiersInstances() as $modifier) {
            $data = $modifier->modifyData($data);
        }

        return $data;
    }

    /**
     * Disable for collection processing
     *
     * @param Filter $filter
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function addFilter(Filter $filter)
    {
        return null;
    }

    /**
     * @param ItemInterface $menuItem
     * @return array
     */
    private function convertToArray(ItemInterface $menuItem): array
    {
        $valueFieldName = $this->valueLookup->getValueFieldNameByType($menuItem->getTypeId());

        return [
            'item_id' => $menuItem->getId(),
            'name' => $menuItem->getName(),
            'store_id' => $menuItem->getStoreId(),
            'priority' => $menuItem->getPriority(),
            'parent_id' => $menuItem->getPriority(),
            'type' => $menuItem->getTypeId(),
            'is_active' => $menuItem->isActive() ? '1' : '0',
            $valueFieldName => $menuItem->getValue(),
        ];
    }

    /**
     * {@inheritdoc}
     * @throws LocalizedException
     */
    public function getMeta()
    {
        $meta = parent::getMeta();

        foreach ($this->pool->getModifiersInstances() as $modifier) {
            $meta = $modifier->modifyMeta($meta);
        }

        return $meta;
    }
}
