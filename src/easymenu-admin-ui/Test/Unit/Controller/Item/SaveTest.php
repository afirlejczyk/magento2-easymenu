<?php

namespace AMF\EasyMenuAdminUi\Test\Unit\Controller\Adminhtml\Item;

use AMF\EasyMenuAdminUi\Controller\Adminhtml\Item\Builder;
use AMF\EasyMenuAdminUi\Controller\Adminhtml\Item\PostDataProcessor;
use AMF\EasyMenuAdminUi\Controller\Adminhtml\Item\Save as SaveAction;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use AMF\EasyMenuApi\Api\ItemRepositoryInterface;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\View\Result\PageFactory;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class SaveTest extends TestCase
{
    /**
     * @var AuthorizationInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $authorizationMock;
    /**
     * @var Context|\PHPUnit\Framework\MockObject\MockObject
     */
    private $actionContextMock;
    /**
     * @var ItemRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $itemRepositoryMock;
    /**
     * @var Builder|\PHPUnit\Framework\MockObject\MockObject
     */
    private $itemBuilderMock;
    /**
     * @var \AMF\EasyMenuAdminUi\Controller\Adminhtml\Item\Edit
     */
    private $saveAction;
    /**
     * @var RedirectFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $resultRedirectFactory;
    /**
     * @var PostDataProcessor|\PHPUnit\Framework\MockObject\MockObject
     */
    private $postDataProcessorMock;
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $loggerMock;
    /**
     * @var DataPersistorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $dataPersistorMock;
    /**
     * @var RequestInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $requestMock;
    /**
     * @var ManagerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $messageManagerMock;

    protected function setUp()
    {
        $this->requestMock = $this->createMock(RequestInterface::class);
        $this->resultRedirectFactory = $this->createMock(RedirectFactory::class);
        $this->authorizationMock = $this->createMock(AuthorizationInterface::class);

        $this->itemRepositoryMock = $this->createMock(ItemRepositoryInterface::class);
        $this->itemBuilderMock = $this->createMock(Builder::class);

        $this->messageManagerMock = $this->createMock(ManagerInterface::class);

        $this->actionContextMock = $this->createMock(Context::class);
        $this->actionContextMock->method('getRequest')->willReturn($this->requestMock);
        $this->actionContextMock->method('getResultRedirectFactory')->willReturn($this->resultRedirectFactory);
        $this->actionContextMock->method('getMessageManager')->willReturn($this->messageManagerMock);

        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->postDataProcessorMock = $this->createMock(PostDataProcessor::class);
        $this->dataPersistorMock = $this->createMock(DataPersistorInterface::class);

        $this->saveAction = new SaveAction(
            $this->actionContextMock,
            $this->itemRepositoryMock,
            $this->createMock(PageFactory::class),
            $this->itemBuilderMock,
            $this->dataPersistorMock,
            $this->loggerMock,
            $this->postDataProcessorMock
        );
    }

    /**
     * @dataProvider getTestExceptionDatProvider
     *
     * @param string $className
     * @param string $errorMessage
     * @param string $userErrorMessage
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function testItemRepositorySaveWillThrowException(
        string $className, string $errorMessage, string $userErrorMessage
    ) {
        $exception = new $className(__($errorMessage));
        $this->itemRepositoryMock->method('save')
            ->willThrowException($exception);

        $this->loggerMock->expects(self::once())->method('critical')
            ->with($exception);
        $this->messageManagerMock->expects(self::once())
            ->method('addErrorMessage')
            ->with($userErrorMessage);

        $mockItem = $this->createMock(ItemInterface::class);
        $this->itemBuilderMock->method('build')->willReturn($mockItem);

        $itemId = '2';
        $name = 'Name';
        $storeId = '2';
        $priority = '3';
        $parentId = '5';
        $type = 'cms';
        $isActive = '0';
        $value = '3';

        $itemData = [
            'item_id' => $itemId,
            'name' => $name,
            'store_id' => $storeId,
            'priority' => $priority,
            'parent_id' => $parentId,
            'type' => $type,
            'is_active' => $isActive,
            'cms_value' => $value,
            'value' => $value,
        ];

        $mockRedirect = $this->createMock(Redirect::class);
        $mockRedirect->method('setPath')
            ->willReturnSelf();
        $this->resultRedirectFactory->method('create')->willReturn($mockRedirect);

        $this->requestMock->method('getParams')->willReturn([]);
        $this->postDataProcessorMock->method('process')->willReturn($itemData);

        self::assertEquals(
            $mockRedirect,
            $this->saveAction->execute()
        );
    }

    public function getTestExceptionDatProvider()
    {
        return [
            [
                LocalizedException::class,
                'Error',
                'Error'
            ],
            [
                \Exception::class,
                'Error',
                'Something went wrong while saving the menu.'
            ],
        ];
    }

    public function testExecuteWillSaveMenuItem()
    {
        $itemId = '2';
        $name = 'Name';
        $storeId = '2';
        $priority = '3';
        $parentId = '5';
        $type = 'cms';
        $isActive = '1';
        $value = '3';

        $itemData = [
            'item_id' => $itemId,
            'name' => $name,
            'store_id' => $storeId,
            'priority' => $priority,
            'parent_id' => $parentId,
            'type' => $type,
            'is_active' => $isActive,
            'cms_value' => $value
        ];

        $resultItemData = [
            'item_id' => $itemId,
            'name' => $name,
            'store_id' => $storeId,
            'priority' => $priority,
            'parent_id' => $parentId,
            'type' => $type,
            'is_active' => $isActive,
            'value' => $value
        ];

        $this->dataPersistorMock->expects(self::once())
            ->method('clear')
            ->with('menu_item');

        $this->dataPersistorMock->expects(self::once())
            ->method('set')
            ->with('menu_item', $itemData);

        $this->postDataProcessorMock->method('process')->with($itemData)
            ->willReturn($resultItemData);

        $this->requestMock->method('getParams')->willReturn($itemData);
        $mockItem = $this->createMock(ItemInterface::class);

        $this->itemBuilderMock->method('build')->willReturn($mockItem);

        $this->itemRepositoryMock->expects(self::once())
            ->method('save')
            ->with($mockItem);

        $this->messageManagerMock->expects(self::once())
            ->method('addSuccessMessage')
            ->with(__('You saved menu item.'));

        $mockItem->method('getId')->willReturn($itemId);
        $mockItem->method('getStoreId')->willReturn($storeId);

        $mockRedirect = $this->createMock(Redirect::class);
        $mockRedirect->method('setPath')
            ->with(
                'easymenu/item/edit',
                [
                    'item_id' => $itemId,
                    'store' => $storeId
                ]
            )
            ->willReturnSelf();
        $this->resultRedirectFactory->method('create')->willReturn($mockRedirect);

        self::assertEquals(
            $mockRedirect,
            $this->saveAction->execute()
        );
    }
}
