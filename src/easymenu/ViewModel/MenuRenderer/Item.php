<?php

declare(strict_types=1);

namespace AMF\EasyMenu\ViewModel\MenuRenderer;

/**
 * Responsible to render menu item node
 */
class Item
{
    /**
     * Retrieve css classes for <a> tag
     *
     * @param int $level
     *
     * @return string
     */
    public function getLinkClassAttributes(int $level): string
    {
        if ($level === 0) {
            return 'class="' . implode(' ', ['level-top']) . '"';
        }

        return '';
    }

    /**
     * Retrieve Link css classes
     *
     * @param int $level
     * @param int $childrenCount
     *
     * @return string
     */
    public function getListItemCssClasses(int $level, int $childrenCount): string
    {
        $classes = $this->getClassesList($level, $childrenCount);

        return 'class="' . implode(' ', $classes);
    }

    /**
     * Get Css Classes list
     *
     * @param int $level
     * @param int $childrenCount
     *
     * @return array
     */
    private function getClassesList(int $level, int $childrenCount): array
    {
        $classes = $this->addTopLevelClasses($level);
        $classes[] = $this->getDefaultClass($level);

        if ($childrenCount) {
            $classes[] = 'parent';
        }

        return $classes;
    }

    /**
     * Add Classes for top level child
     *
     * @param int $level
     *
     * @return array
     */
    private function addTopLevelClasses(int $level): array
    {
        return $level === 0 ? ['level-top'] : [];
    }

    /**
     * Get Default Class item
     *
     * @param int $level
     *
     * @return string
     */
    private function getDefaultClass(int $level): string
    {
        return sprintf('level%d', $level);
    }
}
