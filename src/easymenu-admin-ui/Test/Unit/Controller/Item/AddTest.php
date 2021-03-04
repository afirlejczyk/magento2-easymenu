<?php

namespace AMF\EasyMenuAdminUi\Test\Unit\Controller\Adminhtml\Item;

use AMF\EasyMenuAdminUi\Controller\Adminhtml\Item\Add;
use AMF\EasyMenuApi\Api\ItemRepositoryInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\TestCase;

class AddTest extends TestCase
{
    /**
     * @var AuthorizationInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $authorizationMock;
    /**
     * @var \Magento\Backend\App\Action\Context|\PHPUnit\Framework\MockObject\MockObject
     */
    private $actionContextMock;
    /**
     * @var ItemRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $itemRepositoryMock;
    /**
     * @var \AMF\EasyMenuAdminUi\Controller\Adminhtml\Item\Builder|\PHPUnit\Framework\MockObject\MockObject
     */
    private $itemBuilderMock;
    /**
     * @var \Magento\Framework\View\Result\PageFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $resultPageFactoryMock;
    /**
     * @var \AMF\EasyMenuAdminUi\Controller\Adminhtml\Item\Edit
     */
    private $addAction;
    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $resultRedirectFactory;
    /**
     * @var \Magento\Framework\App\RequestInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $requestMock;
    /**
     * @var StoreInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $storeMock;
    /**
     * @var StoreManagerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $storeManagerMock;

    protected function setUp()
    {
        $this->requestMock = $this->createMock(\Magento\Framework\App\RequestInterface::class);
        $this->resultRedirectFactory = $this->createMock(\Magento\Framework\Controller\Result\RedirectFactory::class);
        $this->authorizationMock = $this->createMock(AuthorizationInterface::class);

        $this->actionContextMock = $this->createMock(\Magento\Backend\App\Action\Context::class);
        $this->actionContextMock->method('getRequest')->willReturn($this->requestMock);
        $this->actionContextMock->method('getResultRedirectFactory')->willReturn($this->resultRedirectFactory);

        $this->itemRepositoryMock = $this->createMock(ItemRepositoryInterface::class);
        $this->itemBuilderMock = $this->createMock(\AMF\EasyMenuAdminUi\Controller\Adminhtml\Item\Builder::class);
        $this->resultPageFactoryMock = $this->createMock(\Magento\Framework\View\Result\PageFactory::class);

        $this->storeMock = $this->createMock(StoreInterface::class);
        $this->storeManagerMock = $this->createMock(StoreManagerInterface::class);

        $this->addAction = new Add(
            $this->actionContextMock,
            $this->itemRepositoryMock,
            $this->resultPageFactoryMock,
            $this->itemBuilderMock,
            $this->storeManagerMock
        );
    }

    public function testWillReturnRedirectResult()
    {
        $this->requestMock->method('getParam')->with('store')->willReturn(null);
        $mockRedirect = $this->createMock(\Magento\Backend\Model\View\Result\Redirect::class);
        $this->resultRedirectFactory->method('create')->willReturn($mockRedirect);

        $defaultStoreId = 1;
        $this->storeMock->method('getId')->willReturn($defaultStoreId);

        $this->storeManagerMock->method('getDefaultStoreView')
            ->willReturn($this->storeMock);

        $mockRedirect->method('setPath')
            ->with(
                'easymenu/item/add',
                [
                    'store' => $defaultStoreId,
                ]
            )
            ->willReturnSelf();


        self::assertEquals(
            $mockRedirect,
            $this->addAction->execute()
        );
    }

    public function testExecuteWillCreateNewItemPage()
    {
        $storeId = 1;
        $this->requestMock->method('getParam')->with('store')->willReturn($storeId);

        $mockItem = $this->createMock(\AMF\EasyMenuApi\Api\Data\ItemInterface::class);
        $this->itemBuilderMock->expects($this->once())->method('build')->willReturn($mockItem);

        $pageMock = $this->createMock(\Magento\Backend\Model\View\Result\Page::class);
        $pageConfig = $this->createMock(\Magento\Framework\View\Page\Config::class);
        $pageTitle = $this->createMock(\Magento\Framework\View\Page\Title::class);
        $pageMock->method('getConfig')->willReturn($pageConfig);
        $pageConfig->method('getTitle')->willReturn($pageTitle);

        $pageTitle->method('prepend')->willReturnMap(
            [
                [__('New menu item'), $pageMock],
            ]
        );

        $this->resultPageFactoryMock->method('create')->willReturn($pageMock);

        self::assertEquals(
            $pageMock,
            $this->addAction->execute()
        );
    }
}
