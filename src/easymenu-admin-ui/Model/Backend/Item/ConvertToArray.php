<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\Model\Backend\Item;

use AMF\EasyMenuApi\Api\Data\ItemInterface;
use AMF\EasyMenuApi\Model\Item\ConvertToArrayInterface;

/**
 * @inheritdoc
 */
class ConvertToArray implements ConvertToArrayInterface
{
    /**
     * @inheritdoc
     *
     * @param ItemInterface $item
     *
     * @return array
     */
    public function execute(ItemInterface $item): array
    {
        $cls = 'folder ' . ($item->isActive() ? 'active-category' : 'no-active-category');

        return [
            'text' => $item->getName(),
            'id' => $item->getId(),
            'parent_id' => $item->getParentId(),
            'value' => $item->getValue(),
            'priority' => $item->getPriority(),
            'cls' => $cls,
        ];
    }
}
