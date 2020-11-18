<?php

namespace AMF\EasyMenuAdminUi\Test\Unit\Ui\Component\Item\Form;

use AMF\EasyMenuAdminUi\Model\Locator\LocatorInterface;
use AMF\EasyMenuAdminUi\Ui\Component\Item\Form\CmsPageOptions;
use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Store\Model\Store;
use PHPUnit\Framework\TestCase;
use \Magento\Cms\Api\Data\PageSearchResultsInterface;

/**
 * Unit test for CmsPageOptions class
 */
class CmsPageOptionsTest extends TestCase
{
    /** @var SearchCriteriaBuilderFactory|\PHPUnit\Framework\MockObject\MockObject  */
    private $searchCriteriaBuilderFactoryMock;

    /** @var LocatorInterface|\PHPUnit\Framework\MockObject\MockObject  */
    private $locatorMock;

    /** @var PageRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject  */
    private $pageRepositoryMock;

    /** @var \PHPUnit\Framework\MockObject\MockObject  */
    private $storeMock;

    /** @var CmsPageOptions  */
    private $cmsPageOptions;

    protected function setUp()
    {
        $this->searchCriteriaBuilderFactoryMock = $this->createMock(SearchCriteriaBuilderFactory::class);
        $this->locatorMock = $this->createMock(LocatorInterface::class);
        $this->pageRepositoryMock = $this->createMock(PageRepositoryInterface::class);

        $this->storeMock = $this->getMockBuilder(Store::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->cmsPageOptions = new CmsPageOptions(
            $this->searchCriteriaBuilderFactoryMock,
            $this->locatorMock,
            $this->pageRepositoryMock
        );
    }

    public function testGetAllCmsPageOptionsForCurrentStore()
    {
        $this->storeMock->method('getId')->willReturn('1');
        $this->locatorMock->method('getStore')->willReturn($this->storeMock);

        $pageResultInterface = $this->createMock(PageSearchResultsInterface::class);
        $this->pageRepositoryMock->method('getList')->willReturn($pageResultInterface);

        $searchCriteria = $this->getMockBuilder(SearchCriteriaInterface::class)->getMock();
        $searchCriteriaBuilder = $this->createMock(SearchCriteriaBuilder::class);
        $searchCriteriaBuilder->method('create')->willReturn($searchCriteria);
        $this->searchCriteriaBuilderFactoryMock->method('create')->willReturn($searchCriteriaBuilder);

        $pageResultInterface->method('getItems')->willReturn(
            [
                $this->getCmsPageMock(1, 'Home', true),
                $this->getCmsPageMock(2, 'Cookie policy', true),
            ]
        );

        $this->assertEquals(
            [
                $this->createPageArray(1, 'Home', true),
                $this->createPageArray(2, 'Cookie policy', true),
            ],
            $this->cmsPageOptions->toOptionArray()
        );
    }

    /**
     * @param int $id
     * @param string $title
     * @param bool $isActive
     * @return array
     */
    private function createPageArray(int $id, string $title, bool $isActive): array
    {
        return [
            'value' => $id,
            'label' => $title,
            'is_active' => $isActive,
        ];
    }

    /**
     * @param int $id
     * @param string $title
     * @param bool $isActive
     * @return PageInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function getCmsPageMock(int $id, string $title, bool $isActive)
    {
        $pageMock = $this->createMock(PageInterface::class);
        $pageMock->method('getId')->willReturn($id);
        $pageMock->method('getTitle')->willReturn($title);
        $pageMock->method('isActive')->willReturn($isActive);

        return $pageMock;
    }
}
