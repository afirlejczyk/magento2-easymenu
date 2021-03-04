<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model;

use AMF\EasyMenuApi\Api\Data\ItemInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

/**
 * @codeCoverageIgnore
 */
class Item extends AbstractExtensibleModel implements ItemInterface, IdentityInterface
{
    public const CACHE_TAG = 'easymenu_item';
    public const CACHE_TAG_STORE = 'easymenu_item_store_';

    /**
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * @return array<string>
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public function getIdentities()
    {
        return [
            self::CACHE_TAG_STORE . $this->getStoreId(),
        ];
    }

    /**
     * @return array<string>
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
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
     * @return int|string|null
     */
    public function getId()
    {
        return $this->getData(self::ITEM_ID);
    }

    public function getName(): string
    {
        return (string) $this->getData(self::NAME);
    }

    public function getValue(): string
    {
        return (string) $this->getData(self::VALUE);
    }

    public function getParentId(): int
    {
        return (int) $this->getData(self::PARENT_ID);
    }

    public function getTypeId(): string
    {
        return (string) $this->getData(self::TYPE);
    }

    public function getPriority(): int
    {
        return (int) $this->getData(self::PRIORITY);
    }

    public function getStoreId(): int
    {
        return (int) $this->getData(self::STORE_ID);
    }

    public function isActive(): bool
    {
        return (bool) $this->getData(self::IS_ACTIVE);
    }

    /**
     * @param int|string $id
     *
     * @return Item
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public function setId($id)
    {
        return $this->setData(self::ITEM_ID, $id);
    }

    public function setName(string $name): void
    {
        $this->setData(self::NAME, $name);
    }

    public function setValue(string $value): void
    {
        $this->setData(self::VALUE, $value);
    }

    public function setParentId(int $parent): void
    {
        $this->setData(self::PARENT_ID, $parent);
    }

    public function setTypeId(int $type): void
    {
        $this->setData(self::TYPE, $type);
    }

    public function setPriority(int $priority): void
    {
        $this->setData(self::PRIORITY, $priority);
    }

    public function setStore(int $store): void
    {
        $this->setData(self::STORE_ID, $store);
    }

    public function setIsActive(bool $isActive): void
    {
        $this->getData(self::IS_ACTIVE, $isActive);
    }

    protected function _construct(): void
    {
        $this->_init(ResourceModel\Item::class);
    }
}
