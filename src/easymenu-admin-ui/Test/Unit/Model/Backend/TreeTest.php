<?php

namespace AMF\EasyMenuAdminUi\Test\Unit\Model\Backend;

use AMF\EasyMenuAdminUi\Model\Backend\Tree;
use AMF\EasyMenuApi\Api\Data\ItemSearchResultInterface;
use AMF\EasyMenuApi\Model\BuildTreeInterface;
use AMF\EasyMenuApi\Model\GetItemsByStoreIdInterface;
use Magento\Framework\Data\Tree\Node;
use PHPUnit\Framework\TestCase;

class TreeTest extends TestCase
{
    /**
     * @var GetItemsByStoreIdInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $getItemsByStoreIdMock;
    /**
     * @var BuildTreeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $buildTreeMock;
    /**
     * @var Tree
     */
    private $backendTree;
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $searchResultMock;

    protected function setUp()
    {
        $this->searchResultMock = $this->getMockBuilder(ItemSearchResultInterface::class)->getMock();
        $this->buildTreeMock = $this->createMock(BuildTreeInterface::class);
        $this->getItemsByStoreIdMock = $this->createMock(GetItemsByStoreIdInterface::class);
        $this->backendTree = new Tree($this->getItemsByStoreIdMock, $this->buildTreeMock);
    }

    public function testMenuTreeWillReturnTreeNode()
    {
        $storeId = 10;
        $this->getItemsByStoreIdMock->method('getAll')->with($storeId)->willReturn($this->searchResultMock);

        $node = $this->createMock(Node::class);
        $this->buildTreeMock->method('buildMenuTree')->with($this->searchResultMock)->willReturn($node);

        self::assertEquals(
            $node,
            $this->backendTree->getMenuTree($storeId)
        );
    }
}
