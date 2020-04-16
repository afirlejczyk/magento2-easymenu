<?php declare(strict_types=1);

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
     * @return ItemInterface
     */
    public function setId($id);

    /**
     * @param string $name
     *
     * @return ItemInterface
     */
    public function setName($name);

    /**
     * @param string $value
     *
     * @return ItemInterface
     */
    public function setValue($value);

    /**
     * @param int $parent
     *
     * @return ItemInterface
     */
    public function setParentId($parent);

    /**
     * @param int $type
     *
     * @return ItemInterface
     */
    public function setTypeId($type);

    /**
     * @param int $priority
     *
     * @return ItemInterface
     */
    public function setPriority($priority);

    /**
     * @param int $store
     *
     * @return ItemInterface
     */
    public function setStore($store);

    /**
     * @param bool $isActive
     *
     * @return ItemInterface
     */
    public function setIsActive($isActive);
}
