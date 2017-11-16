<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Model;

use AF\EasyMenu\Api;
use AF\EasyMenu\Api\Data;
use AF\EasyMenu\Api\Data\ItemInterface;
use AF\EasyMenu\Model\Item\Command\DeleteInterface;
use AF\EasyMenu\Model\Item\Command\GetInterface;
use AF\EasyMenu\Model\Item\Command\SaveInterface;

/**
 * Item Repository
 */
class ItemRepository implements Api\ItemRepositoryInterface
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
     * ItemRepository constructor.
     *
     * @param DeleteInterface $commandDelete
     * @param GetInterface $commandGet
     * @param SaveInterface $commandSave
     */
    public function __construct(
        DeleteInterface $commandDelete,
        GetInterface $commandGet,
        SaveInterface $commandSave
    ) {
        $this->commandSave = $commandSave;
        $this->commandGet = $commandGet;
        $this->commandDelete = $commandDelete;
    }

    /**
     * @inheritdoc
     */
    public function save(Data\ItemInterface $item): int
    {
        return $this->commandSave->execute($item);
    }

    /**
     * @inheritdoc
     */
    public function get($itemId): ItemInterface
    {
        return $this->commandGet->execute($itemId);
    }

    /**
     * @inheritdoc
     */
    public function delete(Data\ItemInterface $item)
    {
        $this->commandDelete->execute($item);
    }
}
