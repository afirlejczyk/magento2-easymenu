<?php

namespace AMF\EasyMenuAdminUi\Test\Unit\Controller\Adminhtml\Item;

use AMF\EasyMenuAdminUi\Controller\Adminhtml\Item\Builder;
use AMF\EasyMenuAdminUi\Controller\Adminhtml\Item\Move as MoveAction;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use AMF\EasyMenuApi\Api\ItemRepositoryInterface;
use AMF\EasyMenuApi\Model\ItemMoverInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Message\Collection as MessageCollection;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\View\Element\Messages;
use Magento\Framework\View\LayoutFactory;
use Magento\Framework\View\LayoutInterface;
use Magento\Framework\View\Result\PageFactory;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class MoveTest extends TestCase
{
    /**
     * @var Context|\PHPUnit\Framework\MockObject\MockObject
     */
    private $actionContextMock;
    /**
     * @var Builder|\PHPUnit\Framework\MockObject\MockObject
     */
    private $itemBuilderMock;
    /**
     * @var \AMF\EasyMenuAdminUi\Controller\Adminhtml\Item\Edit
     */
    private $moveAction;
    /**
     * @var ItemMoverInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $itemMoverMock;
    /**
     * @var LayoutFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $layoutFactoryMock;
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|LoggerInterface
     */
    private $loggerMock;
    /**
     * @var JsonFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $resultJsonFactory;
    /**
     * @var RequestInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $requestMock;
    /**
     * @var ItemInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $itemMock;
    /**
     * @var ManagerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $messageManagerMock;
    /**
     * @var Messages|\PHPUnit\Framework\MockObject\MockObject
     */
    private $messageBlockMock;
    /**
     * @var Json|\PHPUnit\Framework\MockObject\MockObject
     */
    private $resultJsonMock;

    protected function setUp()
    {
        $this->messageManagerMock = $this->createMock(ManagerInterface::class);
        $messageCollectionMock = $this->createMock(MessageCollection::class);
        $this->messageManagerMock->method('getMessages')->willReturn($messageCollectionMock);

        $this->itemMoverMock = $this->createMock(ItemMoverInterface::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->resultJsonFactory = $this->createMock(JsonFactory::class);

        $this->requestMock = $this->createMock(RequestInterface::class);


        $this->itemBuilderMock = $this->createMock(Builder::class);
        $this->itemMock = $this->createMock(ItemInterface::class);

        $this->messageBlockMock = $this->createMock(Messages::class);

        $layout = $this->createMock(LayoutInterface::class);
        $layout->method('getMessagesBlock')->willReturn($this->messageBlockMock);

        $this->layoutFactoryMock = $this->createMock(LayoutFactory::class);
        $this->layoutFactoryMock->method('create')->willReturn($layout);

        $this->resultJsonMock = $this->createMock(Json::class);
        $this->resultJsonFactory->method('create')->willReturn($this->resultJsonMock);

        $this->actionContextMock = $this->createMock(Context::class);
        $this->actionContextMock->method('getRequest')->willReturn($this->requestMock);
        $this->actionContextMock->method('getMessageManager')->willReturn($this->messageManagerMock);

        $this->moveAction = new MoveAction(
            $this->actionContextMock,
            $this->createMock(ItemRepositoryInterface::class),
            $this->createMock(PageFactory::class),
            $this->itemBuilderMock,
            $this->itemMoverMock,
            $this->resultJsonFactory,
            $this->loggerMock,
            $this->layoutFactoryMock
        );
    }

    public function testReturnSuccessJsonResult()
    {
        $this->requestMock->method('getParam')
            ->willReturnMap(
                [
                    ['pid', false, 10],
                    ['aid', false, 1]
                ]
            );

        $this->itemBuilderMock->method('build')
            ->willReturn($this->itemMock);
        $this->itemMoverMock->expects(self::once())
            ->method('move')
            ->with($this->itemMock, 10, 1);

        $this->messageBlockMock->method('getGroupedHtml')->willReturn('');

        $this->resultJsonMock->method(
            'setData'
        )->with(
            [
                'messages' => '',
                'error' => false,
            ]
        )->willReturnSelf();

        $this->messageManagerMock
            ->expects(self::once())
            ->method('addSuccessMessage')
            ->with(__('You moved menu item.'));

        $this->moveAction->execute();
    }

    public function testReturnErrorJsonResult()
    {
        $this->requestMock->method('getParam')
            ->willReturnMap(
                [
                    ['pid', false, 10],
                    ['aid', false, 1]
                ]
            );

        $exception = new \Exception();
        $this->itemBuilderMock->method('build')
            ->willReturn($this->itemMock);

        $this->itemMoverMock->expects(self::once())
            ->method('move')
            ->with($this->itemMock, 10, 1)
            ->willThrowException(new \Exception());

        $this->messageManagerMock
            ->expects(self::once())
            ->method('addErrorMessage')
            ->with(__('Error appear while moving item.'));

        $this->loggerMock
            ->expects(self::once())
            ->method('critical')->with($exception);

        $this->messageBlockMock->method('getGroupedHtml')->willReturn('');

        $this->resultJsonMock->method(
            'setData'
        )->with(
            [
                'messages' => '',
                'error' => true,
            ]
        )->willReturnSelf();

        $this->moveAction->execute();
    }
}
