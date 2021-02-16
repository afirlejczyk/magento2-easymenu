<?php

namespace AMF\EasyMenuAdminUi\Test\Unit\Ui\Component\Item\Form;

use AMF\EasyMenuAdminUi\Ui\Component\Item\Form\CategoryTreeBuilder;
use Magento\Catalog\Api\CategoryManagementInterface;
use Magento\Catalog\Api\Data\CategoryTreeInterface;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Serialize\SerializerInterface;
use PHPUnit\Framework\TestCase;

class CategoryTreeBuilderTest extends TestCase
{
    /**
     * @var CategoryTreeBuilder
     */
    private $categoryTreeBuilder;
    /**
     * @var CacheInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $cacheManager;
    /**
     * @var SerializerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $serializer;
    /**
     * @var CategoryManagementInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $categoryManagementMock;

    protected function setUp()
    {
        $this->cacheManager = $this->createMock(CacheInterface::class);
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->categoryManagementMock = $this->createMock(CategoryManagementInterface::class);

        $this->categoryTreeBuilder = new CategoryTreeBuilder(
            $this->cacheManager,
            $this->serializer,
            $this->categoryManagementMock
        );
    }

    public function testReturnResultFromCache()
    {
        $storeRootCategoryId = 1;

        $childFirstIsActive = 1;
        $childFirstCategoryId = 2;
        $childFirstCategoryName = "Category name";

        $expectedResult = [
            1 => [
                'value' => 1,
                'optgroup' => [
                    [
                        'is_active' => $childFirstIsActive,
                        'value' => $childFirstCategoryId,
                        'label' => $childFirstCategoryName,
                    ]
                ]
            ],
            2 => [
                'is_active' => $childFirstIsActive,
                'value' => $childFirstCategoryId,
                'label' => $childFirstCategoryName,
            ]
        ];

        $expectedResultSerialized = json_encode($expectedResult);

        $this->cacheManager->method('load')->willReturn($expectedResultSerialized);
        $this->serializer->method('unserialize')
            ->with($expectedResultSerialized)
            ->willReturn(json_decode($expectedResultSerialized, true));

        self::assertEquals($expectedResult, $this->categoryTreeBuilder->build($storeRootCategoryId));
    }

    public function testBuildCategoryTree()
    {
        $this->cacheManager->method('load')->willReturn('');

        $storeRootCategoryId = 1;

        $childFirstIsActive = 1;
        $childFirstCategoryId = 2;
        $childFirstCategoryName = "Category name";
        $parentId = 1;

        $thirdLevelIsActive = 1;
        $thirdLevelCategoryId = 13;
        $thirdLevelCategoryName = "Category third";
        $thirdLevelParentId = $childFirstCategoryId;

        $expectedResult = [
            1 => [
                'value' => 1,
                'optgroup' => [
                    [
                        'value' => $childFirstCategoryId,
                        'is_active' => $childFirstIsActive,
                        'label' => $childFirstCategoryName,
                        'optgroup' => [
                            [
                                'value' => $thirdLevelCategoryId,
                                'is_active' => $thirdLevelIsActive,
                                'label' => $thirdLevelCategoryName,
                            ]
                        ]
                    ]
                ]
            ],
            2 => [
                'is_active' => $childFirstIsActive,
                'value' => $childFirstCategoryId,
                'label' => $childFirstCategoryName,
                'optgroup' => [
                    [
                        'value' => $thirdLevelCategoryId,
                        'is_active' => $thirdLevelIsActive,
                        'label' => $thirdLevelCategoryName,
                    ]
                ]
            ],
            13 => [
                'value' => $thirdLevelCategoryId,
                'is_active' => $thirdLevelIsActive,
                'label' => $thirdLevelCategoryName,
            ],
        ];

        $topLevelCategory = $this->createMock(CategoryTreeInterface::class);
        $secondLevelCategory = $this->createMock(CategoryTreeInterface::class);
        $thirdLevelCategory = $this->createMock(CategoryTreeInterface::class);

        $thirdLevelCategory->method('getId')->willReturn($thirdLevelCategoryId);
        $thirdLevelCategory->method('getParentId')->willReturn($thirdLevelParentId);
        $thirdLevelCategory->method('getIsActive')->willReturn($thirdLevelIsActive);
        $thirdLevelCategory->method('getName')->willReturn($thirdLevelCategoryName);
        $thirdLevelCategory->method('getChildrenData')->willReturn([]);

        $secondLevelCategory->method('getId')->willReturn($childFirstCategoryId);
        $secondLevelCategory->method('getParentId')->willReturn($parentId);
        $secondLevelCategory->method('getIsActive')->willReturn($childFirstIsActive);
        $secondLevelCategory->method('getName')->willReturn($childFirstCategoryName);
        $secondLevelCategory->method('getChildrenData')->willReturn([$thirdLevelCategory]);

        $topLevelCategory->method('getChildrenData')->willReturn([$secondLevelCategory]);

        $this->categoryManagementMock->method('getTree')->willReturn($topLevelCategory);
        $this->categoryTreeBuilder->build($storeRootCategoryId);

        self::assertEquals(
            $expectedResult,
            $this->categoryTreeBuilder->build($storeRootCategoryId)
        );
    }
}
