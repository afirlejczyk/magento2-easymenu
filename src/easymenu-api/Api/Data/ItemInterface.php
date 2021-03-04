<?php

declare(strict_types=1);

namespace AMF\EasyMenuApi\Api\Data;

/**
 * Represents Menu Item Node
 *
 * Used fully qualified namespaces in annotations for proper work of WebApi request parser
 *
 * @api
 */
interface ItemInterface
{
    /**
     * @const string
     */
    public const TYPE_CATEGORY = 'category';

    /**
     * @const string
     */
    public const TYPE_CMS_PAGE = 'cms';

    /**
     * @const string
     */
    public const TYPE_CUSTOM_LINK = 'external';

    /**
     * @const string
     */
    public const ITEM_ID = 'item_id';

    /**
     * @const string
     */
    public const NAME = 'name';

    /**
     * @const string
     */
    public const VALUE = 'value';

    /**
     * @const string
     */
    public const PARENT_ID = 'parent_id';

    /**
     * @const string
     */
    public const TYPE = 'type';

    /**
     * @const string
     */
    public const PRIORITY = 'priority';

    /**
     * @const string
     */
    public const STORE_ID = 'store_id';

    /**
     * @const string
     */
    public const IS_ACTIVE = 'is_active';

    /**
     * Retrieve Item ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Retrieve Item Name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Retrieve Item Value
     *
     * @return string
     */
    public function getValue(): string;

    /**
     * Retrieve Parent Id
     *
     * @return int
     */
    public function getParentId(): int;

    /**
     * Retrieve Type
     *
     * @return string
     */
    public function getTypeId(): string;

    /**
     * Retrieve Priority
     *
     * @return int
     */
    public function getPriority(): int;

    /**
     * Retrieve Store Id
     *
     * @return int
     */
    public function getStoreId(): int;

    /**
     * @return bool|null
     */
    public function isActive(): bool;

    /**
     * @param int $id
     *
     * @return void
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     */
    public function setId($id);

    /**
     * @param string $name
     *
     * @return void
     */
    public function setName(string $name): void;

    /**
     * @param string $value
     *
     * @return void
     */
    public function setValue(string $value): void;

    /**
     * @param int $parent
     *
     * @return void
     */
    public function setParentId(int $parent): void;

    /**
     * @param int $type
     *
     * @return void
     */
    public function setTypeId(int $type): void;

    /**
     * @param int $priority
     *
     * @return void
     */
    public function setPriority(int $priority): void;

    /**
     * @param int $store
     *
     * @return void
     */
    public function setStore(int $store): void;

    /**
     * @param bool $isActive
     *
     * @return void
     */
    public function setIsActive(bool $isActive): void;
}
