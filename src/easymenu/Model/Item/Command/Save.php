<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model\Item\Command;

use AMF\EasyMenu\Model\ResourceModel\Item as ResourceItem;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

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

    public function __construct(
        ResourceItem $resourceItem,
        LoggerInterface $logger
    ) {
        $this->resource = $resourceItem;
        $this->logger = $logger;
    }

    public function execute(ItemInterface $item): int
    {
        try {
            $this->resource->save($item);

            return (int) $item->getId();
        } catch (LocalizedException $exception) {
            $this->logger->critical($exception);
            throw new LocalizedException(__($exception->getMessage()));
        } catch (\Exception $exception) {
            $this->logger->critical($exception);
            throw new CouldNotSaveException(__('Could not save menu item.'));
        }
    }
}
