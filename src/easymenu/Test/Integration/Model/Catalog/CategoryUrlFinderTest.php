<?php

namespace AMF\EasyMenu\Test\Integration\Model\Catalog;

use PHPUnit\Framework\TestCase;
use Magento\TestFramework\Helper\Bootstrap;
use AMF\EasyMenu\Model\Catalog\CategoryUrlFinder;

class CategoryUrlFinderTest extends TestCase
{
    const FIXTURE_DIRECTORY = __DIR__ . '/../../_files/';

    /** @var CategoryUrlFinder */
    private $urlFinder;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->urlFinder = Bootstrap::getObjectManager()->create(CategoryUrlFinder::class);
    }

    /**
     * @magentoDataFixture loadCategoryRewrites
     * @dataProvider findDataProvider
     *
     * @param int $categoryId
     * @param string $requestPath
     * @param int $storeId
     */
    public function testGetCategoryUrl(int $categoryId, string $requestPath, int $storeId)
    {
        $categoryUrl = $this->urlFinder->getCategoryUrlList($storeId, [$categoryId]);

        self::assertArrayHasKey($categoryId, $categoryUrl);
        self::assertContains($requestPath, $categoryUrl[$categoryId]);
    }

    /**
     * @return array
     */
    public function findDataProvider(): array
    {
        return [
            [3, 'category-url', 1],
            [4, 'string-category', 2],
            [999, 'catalog/category/view/id/999', 90]
        ];
    }

    /**
     * Load category url rewrites
     */
    public static function loadCategoryRewrites() :void
    {
        include self::FIXTURE_DIRECTORY . 'category_rewrites.php';
    }
}
