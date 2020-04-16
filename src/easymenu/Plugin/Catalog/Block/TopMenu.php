<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Plugin\Catalog\Block;

use Magento\Catalog\Helper\Category;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\ResourceModel\Category\StateDependentCollectionFactory;
use Magento\Catalog\Plugin\Block\Topmenu as CatalogTopMenu;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Modify default core plugin if custom menu is enabled
 */
class TopMenu extends CatalogTopMenu
{
    private const XML_PATH_TOP_MENU_ENABLED = 'top_menu/general/enabled';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * TopMenu constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param Category $catalogCategory
     * @param StateDependentCollectionFactory $categoryCollectionFactory
     * @param StoreManagerInterface $storeManager
     * @param Resolver $layerResolver
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Category $catalogCategory,
        StateDependentCollectionFactory $categoryCollectionFactory,
        StoreManagerInterface $storeManager,
        Resolver $layerResolver
    ) {
        parent::__construct($catalogCategory, $categoryCollectionFactory, $storeManager, $layerResolver);

        $this->scopeConfig = $scopeConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeGetHtml(
        \Magento\Theme\Block\Html\Topmenu $subject,
        $outermostClass = '',
        $childrenWrapClass = '',
        $limit = 0
    ) {
        if (! $this->isTopMenuEnabled()) {
            parent::beforeGetHtml($subject, $outermostClass, $childrenWrapClass, $limit);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function beforeGetIdentities(\Magento\Theme\Block\Html\Topmenu $subject)
    {
        if (! $this->isTopMenuEnabled()) {
            parent::beforeGetIdentities($subject);
        }
    }

    /**
     * Check if easymenu is enabled
     *
     * @return bool
     */
    private function isTopMenuEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_TOP_MENU_ENABLED);
    }
}
