<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Block\Html;

use AF\EasyMenu\Model\Item;
use AF\EasyMenu\Model\Tree;
use Magento\Catalog\Model\Category;
use Magento\Cms\Model\Page;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class Topmenu
 */
class Topmenu extends Template implements IdentityInterface
{

    /**
     * @var int
     */
    private $storeId;

    /**
     * @var Tree
     */
    private $tree;

    /**
     * @var \Magento\Framework\Data\Tree\Node
     */
    private $menu;

    /**
     * @var array
     */
    private $identities = [
        Category::CACHE_TAG,
        Page::CACHE_TAG,
    ];

    /**
     * Topmenu constructor.
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param Tree $tree
     * @param array $data
     */
    public function __construct(
        Context $context,
        Tree $tree,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->tree = $tree;
    }

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        parent::_construct();

        $this->addData(
            [
                'cache_lifetime' => 86400,
                'cache_tags' => $this->getIdentities(),
            ]
        );
    }

    /**
     * @return array
     */
    public function getIdentities()
    {
        $this->identities[] = Item::CACHE_TAG_STORE . $this->getStoreId();

        return $this->identities;
    }

    /**
     * @return array
     */
    public function getCacheKeyInfo()
    {
        $cacheKey = parent::getCacheKeyInfo();
        $cacheKey[] = 'TOP_NAVIGATION';

        return $cacheKey;
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        $menuTree = $this->getMenu();

        return $this->renderMenu($menuTree);
    }

    /**
     * @return Node
     */
    public function getMenu()
    {
        if (null === $this->menu) {
            $storeId = $this->_storeManager->getStore()->getId();
            $this->menu = $this->tree->getMenuTree($storeId);
        }

        return $this->menu;
    }

    /**
     * @param Node $menuTree
     * @param int $level
     *
     * @return string
     */
    public function renderMenu(
        Node $menuTree,
        $level = 0
    ) {
        $children = $menuTree->getChildren();
        $childrenCount = $children->count();

        $html = '';

        foreach ($children as $child) {
            $url = $child->getUrl();

            $html .= '<li ' . $this->getMenuItemAttributes($level, $childrenCount) . '">';
            $classAttributes = $this->getLinkClassAttributes($level);

            $target = '';

            if ($child->getOpenLinkInNewWindow()) {
                $target = ' target="_blank" ';
            }

            $html .= '<a ' . $classAttributes . ' href="' . $url . '"' . $target . 'id="item-' . $child->getId() . '">'
                . $child->getName() . '</a>';

            $html .= $this->addSubMenu($child, $level + 1);
            $html .= '</li>';
        }

        return $html;
    }

    /**
     * Add sub menu HTML code for current menu item
     *
     * @param \Magento\Framework\Data\Tree\Node $child
     * @param string $childLevel
     *
     * @return string HTML code
     */
    protected function addSubMenu(Node $child, $childLevel)
    {
        $html = '';

        if (!$child->hasChildren()) {
            return $html;
        }

        $html .= '<ul class="level' . $childLevel . ' submenu ' . '">';
        $html .= $this->renderMenu($child, $childLevel);
        $html .= '</ul>';

        return $html;
    }

    /**
     * @param int $level
     * @param int $childrenCount
     *
     * @return string
     */
    private function getMenuItemAttributes($level, $childrenCount)
    {
        $classes = [sprintf('level%d', $level)];

        if (0 === $level) {
            $classes[] = 'level-top';
        }

        if ($childrenCount) {
            $classes[] .= 'parent';
        }

        return 'class="' . implode(' ', $classes);
    }

    /**
     * @param int $level
     *
     * @return string
     */
    private function getLinkClassAttributes($level)
    {
        $attributes = [];

        if (0 === $level) {
            $attributes[] = 'level-top';
        }

        return !empty($attributes) ? 'class="' . implode(' ', $attributes) . '"' : '';
    }

    /**
     * @return int
     */
    public function getStoreId()
    {
        if (null === $this->storeId) {
            $this->storeId = $this->_storeManager->getStore()->getId();
        }

        return $this->storeId;
    }
}
