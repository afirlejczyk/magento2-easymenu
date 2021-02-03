<?php

use AMF\EasyMenuAdminUi\Model\Locator\LocatorInterface;
use AMF\EasyMenuAdminUi\ViewModel\Tree;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use AMF\EasyMenuApi\Model\MenuTreeInterface;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Api\Data\StoreInterface;
use PHPUnit\Framework\TestCase;

class TreeTest extends TestCase
{
    /**
     * @var LocatorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $locatorMock;
    /**
     * @var SerializerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $jsonEncoderMock;
    /**
     * @var MenuTreeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $menuTreeMock;
    /**
     * @var Tree
     */
    private $treeViewModel;
    /**
     * @var ItemInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $itemMock;
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|StoreInterface
     */
    private $storeMock;

    protected function setUp()
    {
        $this->itemMock = $this->createMock(ItemInterface::class);
        $this->storeMock = $this->createMock(StoreInterface::class);

        $this->locatorMock = $this->createMock(LocatorInterface::class);
        $this->jsonEncoderMock = $this->createMock(SerializerInterface::class);
        $this->menuTreeMock = $this->createMock(MenuTreeInterface::class);

        $this->treeViewModel = new Tree(
            $this->locatorMock,
            $this->menuTreeMock,
            $this->jsonEncoderMock
        );
    }

    public function testGetStoreId()
    {
        $this->storeMock->method('getId')->willReturn('10');
        $this->locatorMock->method('getStore')->willReturn($this->storeMock);

        self::assertTrue(10 === $this->treeViewModel->getStoreId());
    }

    public function testGetItemId()
    {
        $this->itemMock->method('getId')->willReturn('10');
        $this->locatorMock->method('getMenuItem')->willReturn($this->itemMock);

        self::assertTrue(10 === $this->treeViewModel->getItemId());
    }

    public function testGetTreeJson()
    {
        $rootNode = $this->createPartialMock(
            Node::class,
            [
                'getData',
                'getChildren',
                'hasChildren',
            ]
        );

        $nodeMock = $this->createChildNode();

        $node2Mock = $this->createChildNode();

        $nodeMock->method('getChildren')->willReturn([$node2Mock]);
        $nodeMock->method('hasChildren')->willReturn(true);

        $rootNode->method('getChildren')->willReturn([$nodeMock]);

        $this->menuTreeMock->method('getMenuTree')->willReturn($rootNode);

        $expectedTree = [
            [
                'id' => 1,
                'title' => 'Title',
                'children' => [
                    [
                        'id' => 1,
                        'title' => 'Title',
                    ]
                ]
            ]
        ];

        $this->jsonEncoderMock
            ->method('serialize')
            ->willReturnCallback(
                function ($expectedTree) {
                    return json_encode($expectedTree, true);
                }
            );

        self::assertEquals(
            $expectedTree,
            $this->treeViewModel->getMenuItemTree()
        );

        self::assertEquals(
            json_encode($expectedTree),
            $this->treeViewModel->getTreeJson()
        );
    }

    /**
     * @return Node|\PHPUnit\Framework\MockObject\MockObject
     */
    private function createChildNode()
    {
        $nodeMock = $this->createPartialMock(
            Node::class,
            [
                'getData',
                'getChildren',
                'hasChildren'
            ]
        );

        $nodeMock->method('getData')->willReturn([
                'id' => 1,
                'title' => 'Title'
            ]
        );

        return $nodeMock;
    }
}
