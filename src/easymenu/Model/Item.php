<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model;

use AMF\EasyMenuApi\Api\Data\ItemInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

/**
 * {@inheritdoc}
 *
 * @codeCoverageIgnore
 */
class Item extends AbstractExtensibleModel implements ItemInterface, IdentityInterface
{
    const CACHE_TAG = 'easymenu_item';

    const CACHE_TAG_STORE = 'easymenu_item_store_';

    protected $_cacheTag = self::CACHE_TAG;

    /**
     * @return array
     */
    public function getIdentities()
    {
        return [
            self::CACHE_TAG_STORE . $this->getStoreId(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getCacheTags()
    {
        $cacheTags = parent::getCacheTags();

        if (is_array($cacheTags)) {
            $cacheTags[] = self::CACHE_TAG_STORE . $this->getStoreId();
        }

        return $cacheTags;
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\AMF\EasyMenu\Model\ResourceModel\Item::class);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getData(self::ITEM_ID);
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return (string) $this->getData(self::NAME);
    }

    /**
     * @inheritdoc
     */
    public function getValue(): string
    {
        return (string) $this->getData(self::VALUE);
    }

    /**
     * @inheritdoc
     */
    public function getParentId(): int
    {
        return (int) $this->getData(self::PARENT_ID);
    }

    /**
     * @inheritdoc
     */
    public function getTypeId(): string
    {
        return (string) $this->getData(self::TYPE);
    }

    /**
     * @inheritdoc
     */
    public function getPriority(): int
    {
        return (int) $this->getData(self::PRIORITY);
    }

    /**
     * @inheritdoc
     */
    public function getStoreId(): int
    {
        return (int) $this->getData(self::STORE_ID);
    }

    /**
     * @inheritdoc
     */
    public function isActive(): bool
    {
        return (bool) $this->getData(self::IS_ACTIVE);
    }

    /**
     * @inheritdoc
     */
    public function setId($id)
    {
        return $this->setData(self::ITEM_ID, $id);
    }

    /**
     * @inheritdoc
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritdoc
     */
    public function setValue($value)
    {
        return $this->setData(self::VALUE, $value);
    }

    /**
     * @inheritdoc
     */
    public function setParentId($parent)
    {
        return $this->setData(self::PARENT_ID, $parent);
    }

    /**
     * @inheritdoc
     */
    public function setTypeId($type)
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * @inheritdoc
     */
    public function setPriority($priority)
    {
        return $this->setData(self::PRIORITY, $priority);
    }

    /**
     * @inheritdoc
     */
    public function setStore($store)
    {
        return $this->setData(self::STORE_ID, $store);
    }

    /**
     * @inheritdoc
     */
    public function setIsActive($isActive)
    {
        return $this->getData(self::IS_ACTIVE, $isActive);
    }
}
