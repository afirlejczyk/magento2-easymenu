<?php

namespace AMF\EasyMenuAdminUi\Test\Unit\Controller\Adminhtml\Item;

use AMF\EasyMenuApi\Api\ItemRepositoryInterface;
use Magento\Framework\AuthorizationInterface;
use PHPUnit\Framework\TestCase;

class EditTest extends TestCase
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
    private $edit;
    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $resultRedirectFactory;

    protected function setUp()
    {
        $request = $this->createMock(\Magento\Framework\App\RequestInterface::class);
        $this->resultRedirectFactory = $this->createMock(\Magento\Framework\Controller\Result\RedirectFactory::class);
        $this->authorizationMock = $this->createMock(AuthorizationInterface::class);

        $this->actionContextMock = $this->createMock(\Magento\Backend\App\Action\Context::class);
        $this->actionContextMock->method('getRequest')->willReturn($request);
        $this->actionContextMock->method('getResultRedirectFactory')->willReturn($this->resultRedirectFactory);

        $this->itemRepositoryMock = $this->createMock(ItemRepositoryInterface::class);
        $this->itemBuilderMock = $this->createMock(\AMF\EasyMenuAdminUi\Controller\Adminhtml\Item\Builder::class);
        $this->resultPageFactoryMock = $this->createMock(\Magento\Framework\View\Result\PageFactory::class);

        $this->edit = new \AMF\EasyMenuAdminUi\Controller\Adminhtml\Item\Edit(
            $this->actionContextMock,
            $this->itemRepositoryMock,
            $this->resultPageFactoryMock,
            $this->itemBuilderMock
        );

    }

    public function testExecuteWillReturnRedirectResult()
    {
        $mockItem = $this->createMock(\AMF\EasyMenuApi\Api\Data\ItemInterface::class);
        $mockItem->method('getId')->willReturn(null);

        $this->itemBuilderMock->method('build')->willReturn($mockItem);

        $mockRedirect = $this->createMock(\Magento\Backend\Model\View\Result\Redirect::class);
        $mockRedirect->method('setPath')->willReturnSelf();
        $this->resultRedirectFactory->method('create')->willReturn($mockRedirect);


        self::assertEquals(
            $mockRedirect,
            $this->edit->execute()
        );
    }

    public function testExecuteWillCreatePage()
    {
        $itemName = "Category item";
        $mockItem = $this->createMock(\AMF\EasyMenuApi\Api\Data\ItemInterface::class);
        $mockItem->method('getId')->willReturn(10);
        $mockItem->method('getName')->willReturn($itemName);
        $this->itemBuilderMock->method('build')->willReturn($mockItem);

        $pageMock = $this->createMock(\Magento\Backend\Model\View\Result\Page::class);
        $pageConfig = $this->createMock(\Magento\Framework\View\Page\Config::class);
        $pageTitle = $this->createMock(\Magento\Framework\View\Page\Title::class);
        $pageMock->method('getConfig')->willReturn($pageConfig);
        $pageConfig->method('getTitle')->willReturn($pageTitle);

        $pageTitle->method('prepend')->willReturnMap(
            [
                [__('Easy Menu'), $pageMock],
                [$itemName, $pageMock],
            ]
        );

        $this->resultPageFactoryMock->method('create')->willReturn($pageMock);

        self::assertEquals(
            $pageMock,
            $this->edit->execute()
        );
    }
}
