<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Ui\Component\Item\Form;

use AF\EasyMenu\Model\Item;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Options for Menu Type
 */
class LinkType implements OptionSourceInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = [];

        foreach (self::toArray() as $value => $label) {
            $result[] = [
                'value' => $value,
                'label' => $label,
            ];
        }

        return $result;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public static function toArray()
    {
        return [
            Item::TYPE_CATEGORY => __('Category'),
            Item::TYPE_CMS_PAGE => __('CMS Page'),
            Item::TYPE_CUSTOM_LINK => __('Custom Link'),
        ];
    }
}
