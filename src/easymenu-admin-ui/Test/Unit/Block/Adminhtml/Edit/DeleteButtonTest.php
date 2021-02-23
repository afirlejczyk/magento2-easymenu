<?php

namespace AMF\EasyMenuAdminUi\Test\Unit\Block\Adminhtml\Edit;

use AMF\EasyMenuAdminUi\Block\Adminhtml\Edit\DeleteButton;
use AMF\EasyMenuAdminUi\Model\Locator\LocatorInterface;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Escaper;
use Magento\Framework\UrlInterface;
use PHPUnit\Framework\TestCase;

class DeleteButtonTest extends TestCase
{
    /**
     * @var UrlInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $urlBuilderMock;
    /**
     * @var Context|\PHPUnit\Framework\MockObject\MockObject
     */
    private $contextMock;
    /**
     * @var LocatorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $locatormock;
    /**
     * @var ItemInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $menuItemMock;
    /**
     * @var DeleteButton
     */
    private $deleteButton;
    /**
     * @var Escaper|\PHPUnit\Framework\MockObject\MockObject
     */
    private $escaperMock;

    protected function setUp()
    {
        $this->escaperMock = $this->createMock(Escaper::class);
        $this->urlBuilderMock = $this->createMock(UrlInterface::class);
        $this->contextMock = $this->createMock(Context::class);

        $this->contextMock->method('getEscaper')->willReturn($this->escaperMock);
        $this->contextMock->method('getUrlBuilder')->willReturn($this->urlBuilderMock);
        $this->locatormock = $this->createMock(LocatorInterface::class);

        $this->menuItemMock = $this->createMock(ItemInterface::class);
        $this->locatormock->method('getMenuItem')->willReturn($this->menuItemMock);

        $this->deleteButton = new DeleteButton(
            $this->contextMock,
            $this->locatormock,
            []
        );
    }

    public function testGetNotEmptyDataWhenItemExist()
    {
        $itemId = 10;
        $deleteConfirmMsg = __('Are you sure you want to do this?');
        $deleteUrl =  "easymenu/item/delete/id/{$itemId}";
        $this->menuItemMock->method('getId')->willReturn($itemId);
        $this->escaperMock->method('escapeJs')->willReturn($deleteConfirmMsg);
        $this->escaperMock->method('escapeHtml')->willReturn($deleteConfirmMsg);

        $this->urlBuilderMock->method('getUrl')
            ->with('easymenu/item/delete', [
                'id' => $itemId,
                '_current' => true,
                '_query' => ['isAjax' => null],
            ])
            ->willReturn($deleteUrl);

        self::assertEquals(
            [
                'id' => 'delete',
                'label' => __('Delete Item'),
                'on_click' => "deleteConfirm('" . $deleteConfirmMsg .
                    "', '" . $deleteUrl . "')",
                'class' => 'delete',
                'sort_order' => 10,
            ],
            $this->deleteButton->getButtonData()
        );
    }
}
