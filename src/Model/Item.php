<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Model;

use AF\EasyMenu\Api\Data\ItemInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Item
 */
class Item extends AbstractModel implements ItemInterface, \Magento\Framework\DataObject\IdentityInterface
{

    const TYPE_CATEGORY = 1;

    const TYPE_CMS_PAGE = 2;

    const TYPE_CUSTOM_LINK = 3;

    const CACHE_TAG = 'easymenu_item';

    const CACHE_TAG_STORE = 'easymenu_item_store_';

    protected $_cacheTag = self::CACHE_TAG;

    /**
     * @return array
     */
    public function getIdentities()
    {
        $identities = [
            self::CACHE_TAG_STORE . $this->getStoreId(),
        ];

        return $identities;
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
        $this->_init('AF\EasyMenu\Model\ResourceModel\Item');
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
    public function getName()
    {
        return (string) $this->getData(self::NAME);
    }

    /**
     * @inheritdoc
     */
    public function getValue()
    {
        return (string) $this->getData(self::VALUE);
    }

    /**
     * @inheritdoc
     */
    public function getParentId()
    {
        return (int) $this->getData(self::PARENT_ID);
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return (int) $this->getData(self::TYPE);
    }

    /**
     * @inheritdoc
     */
    public function getPriority()
    {
        return (int) $this->getData(self::PRIORITY);
    }

    /**
     * @inheritdoc
     */
    public function getStoreId()
    {
        return (int) $this->getData(self::STORE_ID);
    }

    /**
     * @inheritdoc
     */
    public function openLinkInNewWindow()
    {
        return (bool) $this->getData(self::OPEN_LINK_IN_NEW_WINDOW);
    }

    /**
     * @inheritdoc
     */
    public function isActive()
    {
        return $this->getData(self::IS_ACTIVE);
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
    public function setType($type)
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
    public function setOpenLinkInNewWindow($openLinkInNewWindow)
    {
        return $this->setData(self::OPEN_LINK_IN_NEW_WINDOW, $openLinkInNewWindow);
    }

    /**
     * @inheritdoc
     */
    public function setIsActive($isActive)
    {
        return $this->getData(self::IS_ACTIVE, $isActive);
    }

    /**
     * @return bool
     */
    public function isCategoryItem()
    {
        if ($this->getType() === self::TYPE_CATEGORY) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isCmsPageItem()
    {
        if ($this->getType() === self::TYPE_CMS_PAGE) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isCustomLink()
    {
        if (self::TYPE_CUSTOM_LINK === $this->getType()) {
            return true;
        }

        return false;
    }
}
