<?php

namespace AMF\EasyMenuAdminUi\Test\Unit\Ui\Component\Item\Form;

use AMF\EasyMenuAdminUi\Model\Locator\LocatorInterface;
use AMF\EasyMenuAdminUi\Ui\Component\Item\Form\CategoryOptions;
use AMF\EasyMenuAdminUi\Ui\Component\Item\Form\CategoryTreeBuilder;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\Store;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class CategoryOptionsTest extends TestCase
{
    /**
     * @var StoreInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $storeMock;
    /**
     * @var LocatorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $locatorMock;
    /**
     * @var CategoryTreeBuilder|\PHPUnit\Framework\MockObject\MockObject
     */
    private $categoryTreeBuilderMock;
    /**
     * @var CategoryOptions
     */
    private $categoryOptions;

    protected function setUp()
    {
        $this->storeMock = $this->createMock(Store::class);
        $this->locatorMock = $this->createMock(LocatorInterface::class);
        $this->locatorMock->method('getStore')->willReturn($this->storeMock);

        $this->categoryTreeBuilderMock = $this->createMock(CategoryTreeBuilder::class);

        $this->categoryOptions = new CategoryOptions(
            $this->categoryTreeBuilderMock,
            $this->locatorMock
        );
    }

    public function testGetTreeRootId()
    {
        $reflector = new ReflectionClass(CategoryOptions::class);
        $method = $reflector->getMethod('getRootCategoryId');
        $method->setAccessible(true);

        $this->storeMock->method('getRootCategoryId')->willReturn(0);

        self::assertEquals(1, $method->invokeArgs($this->categoryOptions, []));
    }

    public function testGetOptionArray()
    {
        $this->storeMock->method('getRootCategoryId')->willReturn(10);

        $childFirstIsActive = 1;
        $childFirstCategoryId = 2;
        $childFirstCategoryName = "Category name";
        $categoryTree = [
            10 => [
                'value' => 1,
                'optgroup' => [
                    [
                        'is_active' => $childFirstIsActive,
                        'value' => $childFirstCategoryId,
                        'label' => $childFirstCategoryName,
                    ]
                ]
            ],
            $childFirstCategoryId => [
                'is_active' => $childFirstIsActive,
                'value' => $childFirstCategoryId,
                'label' => $childFirstCategoryName,
            ]
        ];

        $this->categoryTreeBuilderMock->method('build')->willReturn($categoryTree);

        self::assertEquals(
            [
                [
                    'is_active' => $childFirstIsActive,
                    'value' => $childFirstCategoryId,
                    'label' => $childFirstCategoryName,
                ]
            ],
            $this->categoryOptions->toOptionArray()
        );
    }

    public function testGetOptionArrayForRootTreeId()
    {
        $categoryTree = [
            1 => [
                'value' => 1,
                'optgroup' => []
            ]
        ];

        $this->storeMock->method('getRootCategoryId')->willReturn(11);
        $this->categoryTreeBuilderMock->method('build')->willReturn($categoryTree);

        self::assertEquals(
            [],
            $this->categoryOptions->toOptionArray()
        );
    }
}
