<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model\Item\Command;

use AMF\EasyMenu\Model\ResourceModel\Item as ResourceItem;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Psr\Log\LoggerInterface;

/**
 * @inheritdoc
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
     * Delete constructor.
     *
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
    public function execute(ItemInterface $item): void
    {
        try {
            $this->resource->delete($item);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
            throw new CouldNotDeleteException(__('Could nod delete menu item.'));
        }
    }
}
