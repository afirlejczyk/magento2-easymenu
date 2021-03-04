<?php

namespace AMF\EasyMenuAdminUi\Test\Unit\Controller\Adminhtml\Item;

use AMF\EasyMenuAdminUi\Controller\Adminhtml\Item\Delete as ActionDelete;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use AMF\EasyMenuApi\Api\ItemRepositoryInterface;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
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
    /**
     * @var \Magento\Framework\Message\ManagerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $messageManagerMock;
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\Psr\Log\LoggerInterface
     */
    private $loggerMock;
    /**
     * @var ItemInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $mockItem;
    /**
     * @var Redirect|\PHPUnit\Framework\MockObject\MockObject
     */
    private $mockRedirect;

    protected function setUp()
    {
        $request = $this->createMock(\Magento\Framework\App\RequestInterface::class);
        $this->resultRedirectFactory = $this->createMock(\Magento\Framework\Controller\Result\RedirectFactory::class);
        $this->authorizationMock = $this->createMock(AuthorizationInterface::class);
        $this->messageManagerMock = $this->createMock(\Magento\Framework\Message\ManagerInterface::class);

        $this->actionContextMock = $this->createMock(\Magento\Backend\App\Action\Context::class);
        $this->actionContextMock->method('getRequest')->willReturn($request);
        $this->actionContextMock->method('getResultRedirectFactory')->willReturn($this->resultRedirectFactory);
        $this->actionContextMock->method('getMessageManager')->willReturn($this->messageManagerMock);

        $this->itemRepositoryMock = $this->createMock(ItemRepositoryInterface::class);
        $this->itemBuilderMock = $this->createMock(\AMF\EasyMenuAdminUi\Controller\Adminhtml\Item\Builder::class);
        $this->resultPageFactoryMock = $this->createMock(\Magento\Framework\View\Result\PageFactory::class);
        $this->loggerMock = $this->createMock(\Psr\Log\LoggerInterface::class);

        $this->mockItem = $this->createMock(ItemInterface::class);
        $this->mockRedirect = $this->createMock(Redirect::class);
        $this->resultRedirectFactory->method('create')->willReturn($this->mockRedirect);

        $this->actionDelete = new ActionDelete(
            $this->actionContextMock,
            $this->itemRepositoryMock,
            $this->resultPageFactoryMock,
            $this->itemBuilderMock,
            $this->loggerMock
        );

    }

    public function testDeleteItem()
    {
        $this->mockRedirect->method('setPath')->willReturnSelf();
        $this->itemBuilderMock->method('build')->willReturn($this->mockItem);

        $itemId = 9;
        $this->mockItem->method('getId')->willReturn($itemId);

        $this->itemRepositoryMock->expects($this->once())
            ->method('delete')
            ->with($this->mockItem);

        $this->messageManagerMock
            ->expects($this->once())
            ->method('addSuccessMessage')->with(__('You deleted menu item.'));


        $this->actionDelete->execute();
    }

    public function testDeleteItemWillThrowCouldNotDeleteException()
    {
        $this->itemBuilderMock->method('build')->willReturn($this->mockItem);
        $this->mockRedirect->method('setPath')->willReturnSelf();

        $itemId = 9;

        $this->mockItem->method('getId')->willReturn($itemId);

        $exception = new CouldNotDeleteException(__('Could nod delete menu item.'));

        $this->itemRepositoryMock->method('delete')->with($this->mockItem)
            ->willThrowException($exception);

        $this->messageManagerMock->expects($this->once())->method('addErrorMessage')
            ->with(__('Something went wrong while trying to delete menu item.'));

        $this->loggerMock->expects($this->once())
            ->method('critical')->with($exception);

        $this->actionDelete->execute();
    }

    public function testExecuteWillRedirectToAddNewView()
    {
        $storeId = 2;
        $this->mockItem->method('getId')->willReturn(null);
        $this->mockItem->method('getParentId')->willReturn(0);
        $this->mockItem->method('getStoreId')->willReturn($storeId);

        $this->itemBuilderMock->method('build')->willReturn($this->mockItem);

        $this->mockRedirect->method('setPath')
            ->with(
                '*/*/add',
                [
                    '_current' => false,
                    'store' => $storeId,
                    'item_id' => null,
                ]
            )
            ->willReturnSelf();
        $this->resultRedirectFactory->method('create')->willReturn($this->mockRedirect);

        self::assertEquals(
            $this->mockRedirect,
            $this->actionDelete->execute()
        );
    }

    public function testExecuteWillRedirectToEditView()
    {
        $storeId = 2;
        $parentId = 12;
        $this->mockItem->method('getId')->willReturn(null);
        $this->mockItem->method('getParentId')->willReturn($parentId);
        $this->mockItem->method('getStoreId')->willReturn($storeId);

        $this->itemBuilderMock->method('build')->willReturn($this->mockItem);

        $this->mockRedirect->method('setPath')
            ->with(
                'easymenu/*/edit',
                [
                    '_current' => false,
                    'item_id' => $parentId,
                ]
            )
            ->willReturnSelf();
        $this->resultRedirectFactory->method('create')->willReturn($this->mockRedirect);

        self::assertEquals(
            $this->mockRedirect,
            $this->actionDelete->execute()
        );
    }
}
