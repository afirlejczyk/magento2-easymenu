<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Test\Unit\Model;

use AMF\EasyMenu\Model\Item\ConvertToArray;
use AMF\EasyMenu\Model\Item\UrlResolver;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ConvertToArrayTest extends TestCase
{
    /** @var ItemInterface|\PHPUnit\Framework\MockObject\MockObject */
    private $item;

    /** @var UrlResolver|\PHPUnit\Framework\MockObject\MockObject  */
    private $urlResolver;

    /** @var ConvertToArray */
    private $convertToArray;

    protected function setUp()
    {
        $this->item = $this->getMockBuilder(ItemInterface::class)->getMock();
        $this->urlResolver = $this->createMock(UrlResolver::class);
        $this->convertToArray = new ConvertToArray($this->urlResolver);
    }

    public function testExecute()
    {
        $url = 'www.test.local';
        $name = 'Category 1';
        $itemId = 1;

        $this->urlResolver->method('getUrl')
            ->with($this->item)
            ->willReturn($url);

        $this->item->method('getId')->willReturn($itemId);
        $this->item->method('getName')->willReturn($name);

        self::assertEquals(
            [
                'id' => $itemId,
                'url' => $url,
                'name' => $name,
            ], $this->convertToArray->execute($this->item)
        );
    }
}
