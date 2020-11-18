<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\Ui\Component\Item\Form;

use AMF\EasyMenuApi\Api\Data\ItemInterface;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Options for Menu Type
 */
class LinkType implements OptionSourceInterface
{
    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = [];
        foreach ($this->toArray() as $value => $label) {
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
    private function toArray(): array
    {
        return [
            ItemInterface::TYPE_CATEGORY => __('Category'),
            ItemInterface::TYPE_CMS_PAGE => __('CMS Page'),
            ItemInterface::TYPE_CUSTOM_LINK => __('Custom Link'),
        ];
    }
}
