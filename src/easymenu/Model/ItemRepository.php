<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model;

use AMF\EasyMenu\Model\Item\Command\DeleteInterface;
use AMF\EasyMenu\Model\Item\Command\GetInterface;
use AMF\EasyMenu\Model\Item\Command\GetListInterface;
use AMF\EasyMenu\Model\Item\Command\SaveInterface;
use AMF\EasyMenuApi\Api\Data;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use AMF\EasyMenuApi\Api\ItemRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

class ItemRepository implements ItemRepositoryInterface
{
    /**
     * @var SaveInterface
     */
    private $commandSave;

    /**
     * @var GetInterface
     */
    private $commandGet;

    /**
     * @var DeleteInterface
     */
    private $commandDelete;

    /**
     * @var GetListInterface
     */
    private $commandGetList;

    /**
     * ItemRepository constructor.
     *
     * @param DeleteInterface $commandDelete
     * @param GetListInterface $commandGetList
     * @param GetInterface $commandGet
     * @param SaveInterface $commandSave
     */
    public function __construct(
        DeleteInterface $commandDelete,
        GetListInterface $commandGetList,
        GetInterface $commandGet,
        SaveInterface $commandSave
    ) {
        $this->commandSave = $commandSave;
        $this->commandGet = $commandGet;
        $this->commandGetList = $commandGetList;
        $this->commandDelete = $commandDelete;
    }

    public function save(Data\ItemInterface $item): int
    {
        return $this->commandSave->execute($item);
    }

    public function get($itemId): ItemInterface
    {
        return $this->commandGet->execute($itemId);
    }

    public function delete(Data\ItemInterface $item): void
    {
        $this->commandDelete->execute($item);
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        return $this->commandGetList->execute($searchCriteria);
    }
}
