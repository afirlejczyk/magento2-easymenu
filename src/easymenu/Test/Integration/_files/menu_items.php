<?php

use AMF\EasyMenu\Model\Item;
use AMF\EasyMenuApi\Api\ItemRepositoryInterface;
use Magento\TestFramework\Helper\Bootstrap;
use AMF\EasyMenuApi\Api\Data\ItemInterfaceFactory;

$objectManager = Bootstrap::getObjectManager();
$itemFactory = $objectManager->get(ItemInterfaceFactory::class);
$itemRepository = $objectManager->get(ItemRepositoryInterface::class);

/** @var Item $item */
$item = $itemFactory->create();
$item->setType('cms');
$item->setValue(1);
$item->setName('Level1item');
$item->setParentId(0);
$item->setPriority(0);
$item->setStore(1);
$item->setIsActive(1);
$itemRepository->save($item);

/** @var Item $item */
$item = $itemFactory->create();
$item->setType('cms');
$item->setValue(1);
$item->setName('Level1item2');
$item->setParentId(0);
$item->setPriority(0);
$item->setStore(1);
$item->setIsActive(1);
$itemRepository->save($item);
