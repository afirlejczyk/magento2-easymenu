<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Model\Item\Command;

use AF\EasyMenu\Api\Data\ItemInterface;
use AF\EasyMenu\Model\ResourceModel\Item as ResourceItem;
use Magento\Framework\Exception\CouldNotSaveException;
use Psr\Log\LoggerInterface;

/**
 * Save Menu Item data command (Service Provider Interface - SPI)
 */
class Save implements SaveInterface
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
    public function execute(ItemInterface $item): int
    {
        try {
            $this->resource->save($item);

            return $item->getId();
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
            throw new CouldNotSaveException(__('Could not save menu item.'));
        }
    }
}
