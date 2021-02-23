<?php

namespace AMF\EasyMenuAdminUi\Test\Unit\Controller\Adminhtml\Item;

use AMF\EasyMenuAdminUi\Controller\Adminhtml\Item\Delete as ActionDelete;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use AMF\EasyMenuApi\Api\ItemRepositoryInterface;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\AuthorizationInterface;
use PHPUnit\Framework\TestCase;

class DeleteTest extends TestCase
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
    private $actionDelete;
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

        $this->loggerMock = $this->createMock(\Psr\Log\LoggerInterface::class);

        $this->actionDelete = new ActionDelete(
            $this->actionContextMock,
            $this->itemRepositoryMock,
            $this->resultPageFactoryMock,
            $this->itemBuilderMock,
            $this->loggerMock
        );

    }

    public function testExecuteWillReturnToAddNewView()
    {
        $storeId = 2;
        $mockItem = $this->createMock(ItemInterface::class);
        $mockItem->method('getId')->willReturn(null);
        $mockItem->method('getParentId')->willReturn(0);
        $mockItem->method('getStoreId')->willReturn($storeId);

        $this->itemBuilderMock->method('build')->willReturn($mockItem);

        $mockRedirect = $this->createMock(Redirect::class);
        $mockRedirect->method('setPath')
            ->with(
                '*/*/add',
                [
                    '_current' => false,
                    'store' => $storeId,
                    'item_id' => null,
                ]
            )
            ->willReturnSelf();
        $this->resultRedirectFactory->method('create')->willReturn($mockRedirect);

        self::assertEquals(
            $mockRedirect,
            $this->actionDelete->execute()
        );
    }

    public function testExecuteWillReturnToEditView()
    {
        $storeId = 2;
        $parentId = 12;
        $mockItem = $this->createMock(ItemInterface::class);
        $mockItem->method('getId')->willReturn(null);
        $mockItem->method('getParentId')->willReturn($parentId);
        $mockItem->method('getStoreId')->willReturn($storeId);

        $this->itemBuilderMock->method('build')->willReturn($mockItem);

        $mockRedirect = $this->createMock(Redirect::class);
        $mockRedirect->method('setPath')
            ->with(
                'easymenu/*/edit',
                [
                    '_current' => false,
                    'item_id' => $parentId,
                ]
            )
            ->willReturnSelf();
        $this->resultRedirectFactory->method('create')->willReturn($mockRedirect);

        self::assertEquals(
            $mockRedirect,
            $this->actionDelete->execute()
        );
    }
}
