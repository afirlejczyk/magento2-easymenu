<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Api\Data;

/**
 * Interface ItemInterface
 */
interface ItemInterface
{
    const ITEM_ID = 'item_id';

    const NAME = 'name';

    const VALUE = 'value';

    const PARENT_ID = 'parent_id';

    const TYPE = 'type';

    const PRIORITY = 'priority';

    const STORE_ID = 'store_id';

    const OPEN_LINK_IN_NEW_WINDOW = 'open_link_in_new_window';

    const IS_ACTIVE = 'is_active';

    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getValue();

    /**
     * @return int
     */
    public function getParentId();

    /**
     * @return int
     */
    public function getType();

    /**
     * @return int
     */
    public function getPriority();

    /**
     * @return int
     */
    public function getStoreId();

    /**
     * @return bool
     */
    public function openLinkInNewWindow();

    /**
     * @return bool|null
     */
    public function isActive();

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
    public function setType($type);

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
     * @param bool $openLinkInNewWindow
     *
     * @return ItemInterface
     */
    public function setOpenLinkInNewWindow($openLinkInNewWindow);

    /**
     * @param bool $isActive
     *
     * @return ItemInterface
     */
    public function setIsActive($isActive);
}
