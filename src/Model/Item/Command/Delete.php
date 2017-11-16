<?php
/**
 * @package  AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE_DIVANTE.txt for license details.
 */

namespace AF\EasyMenu\Model\Item\Command;

use AF\EasyMenu\Api\Data\ItemInterface;
use AF\EasyMenu\Model\ResourceModel\Item as ResourceItem;
use Magento\Framework\Exception\CouldNotDeleteException;
use Psr\Log\LoggerInterface;

/**
 * Delete Menu Item data
 */
class Delete implements DeleteInterface
{

    /**
     * @var ResourceItem
     */
    private $resource;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param ResourceItem $resourceItem
     * @param LoggerInterface $logger
     */
    public function __construct(
        ResourceItem $resourceItem,
        LoggerInterface $logger
    ) {
        $this->resource = $resourceItem;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function execute(ItemInterface $item)
    {
        try {
            $this->resource->delete($item);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
            throw new CouldNotDeleteException(__('Could nod delete menu item.'));
        }
    }
}
