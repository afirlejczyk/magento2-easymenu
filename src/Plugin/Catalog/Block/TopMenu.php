<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 */

namespace AF\EasyMenu\Plugin\Catalog\Block;

use Magento\Catalog\Plugin\Block\Topmenu as CatalogTopMenu;

/**
 * Class TopMenu
 */
class TopMenu extends CatalogTopMenu
{

    const XML_PATH_TOP_MENU_ENABLED = 'top_menu/general/enabled';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * TopMenu constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Catalog\Helper\Category $catalogCategory
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Helper\Category $catalogCategory,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver
    ) {
        parent::__construct($catalogCategory, $categoryCollectionFactory, $storeManager, $layerResolver);

        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Build category tree for menu block.
     *
     * @param \Magento\Theme\Block\Html\Topmenu $subject
     * @param string $outermostClass
     * @param string $childrenWrapClass
     * @param int $limit
     *
     * @return void
     * @SuppressWarnings("PMD.UnusedFormalParameter")
     */
    public function beforeGetHtml(
        \Magento\Theme\Block\Html\Topmenu $subject,
        $outermostClass = '',
        $childrenWrapClass = '',
        $limit = 0
    ) {
        if (!$this->isTopMenuEnabled()) {
            parent::beforeGetHtml($subject, $outermostClass, $childrenWrapClass, $limit);
        }
    }

    /**
     * @param \Magento\Theme\Block\Html\Topmenu $subject
     */
    public function beforeGetIdentities(\Magento\Theme\Block\Html\Topmenu $subject)
    {
        if (!$this->isTopMenuEnabled()) {
            parent::beforeGetIdentities($subject);
        }
    }

    /**
     * @return bool
     */
    private function isTopMenuEnabled()
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_TOP_MENU_ENABLED);
    }
}
