<?php

declare(strict_types=1);

namespace AMF\EasyMenu\ViewModel;

use AMF\EasyMenu\ViewModel\MenuRenderer\Item as MenuItemRenderer;
use Magento\Framework\Data\Tree\Node;

/**
 * Responsible to render menu tree
 */
class MenuRenderer implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var MenuItemRenderer
     */
    private $itemRenderer;

    /**
     * MenuRenderer constructor.
     *
     * @param MenuItemRenderer $itemRenderer
     */
    public function __construct(MenuItemRenderer $itemRenderer)
    {
        $this->itemRenderer = $itemRenderer;
    }

    /**
     * Render Menu Edit Html
     *
     * @param Node $menuTree
     *
     * @return string
     */
    public function render(Node $menuTree): string
    {
        return $this->renderMenu($menuTree, 0);
    }

    /**
     * Render Menu html
     *
     * @param Node $menuTree
     * @param int $level
     *
     * @return string
     */
    private function renderMenu(Node $menuTree, int $level): string
    {
        $children = $menuTree->getChildren();
        $childrenCount = $children->count();

        $html = '';

        foreach ($children as $child) {
            $html .= '<li ' . $this->itemRenderer->getListItemCssClasses($level, $childrenCount) . '">';
            $html .= $this->getLinkHtml($child, $level);
            $html .= $this->addSubMenu($child, $level + 1);
            $html .= '</li>';
        }

        return $html;
    }

    /**
     * Retrieve Link Html
     *
     * @param Node $child
     * @param int $level
     *
     * @return string
     */
    private function getLinkHtml(Node $child, int $level): string
    {
        $classAttributes = $this->itemRenderer->getLinkClassAttributes($level);

        return '<a ' . $classAttributes . ' href="' . $child->getUrl() . '"id="item-' . $child->getId() . '">'
            . $child->getName() . '</a>';
    }

    /**
     * Add sub menu HTML code for current menu item
     *
     * @param Node $child
     * @param int $childLevel
     *
     * @return string HTML code
     */
    private function addSubMenu(Node $child, int $childLevel): string
    {
        $html = '';

        if (! $child->hasChildren()) {
            return $html;
        }

        $html .= "<ul class=\"level${childLevel} submenu \">";
        $html .= $this->renderMenu($child, $childLevel);
        $html .= '</ul>';

        return $html;
    }
}
