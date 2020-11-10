<?php

namespace AMF\EasyMenu\Test\Integration\Model\Item;

use AMF\EasyMenuApi\Api\ItemRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use PHPUnit\Framework\TestCase;
use Magento\TestFramework\Helper\Bootstrap;
use AMF\EasyMenuApi\Model\ItemMoverInterface;

class MoveItemTest extends TestCase
{
    const FIXTURE_DIRECTORY = __DIR__ . '/../../_files/';


    /** @var ItemRepositoryInterface */
    private $itemRepository;

    /** @var  */
    private $searchCriteriaBuilder;

    /** @var ItemMoverInterface */
    private $mover;

    protected function setUp()
    {
        $this->mover = Bootstrap::getObjectManager()->get(ItemMoverInterface::class);
        $this->itemRepository = Bootstrap::getObjectManager()->get(ItemRepositoryInterface::class);
        $this->searchCriteriaBuilder = Bootstrap::getObjectManager()->get(SearchCriteriaBuilder::class);
    }

    /**
     * @magentoDataFixture loadMenu
     */
    public function testChangeParent()
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('name', 'Level1item')
            ->addFilter('parent_id', 0)
            ->create();

        $items = $this->itemRepository->getList($searchCriteria)->getItems();
        self::assertCount(1, $items);

        $level1ItemNr1 = reset($items);

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('name', 'Level1item2')
            ->addFilter('parent_id', 0)
            ->create();

        $items = $this->itemRepository->getList($searchCriteria)->getItems();
        self::assertCount(1, $items);

        $level1ItemNr2 = reset($items);
        $this->mover->move($level1ItemNr2, $level1ItemNr1->getId(), null);

        $childItem = $this->itemRepository->get((int) $level1ItemNr2->getId());
        self::assertEquals($level1ItemNr1->getId(), $childItem->getParentId());
        self::assertEquals(1, $childItem->getPriority());
    }

    /**
     * Load cms pages
     */
    public static function loadMenu() :void
    {
        include self::FIXTURE_DIRECTORY . 'menu_items.php';
    }
}
