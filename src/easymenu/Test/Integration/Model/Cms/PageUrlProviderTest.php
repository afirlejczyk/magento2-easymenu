<?php

namespace AMF\EasyMenu\Test\Integration\Model\Cms;

use PHPUnit\Framework\TestCase;
use Magento\TestFramework\Helper\Bootstrap;
use AMF\EasyMenu\Model\Cms\PageUrlProvider;

/**
 * @magentoDataFixture loadCmsPages
 */
class PageUrlProviderTest extends TestCase
{
    const FIXTURE_DIRECTORY = __DIR__ . '/../../_files/';

    /** @var PageUrlProvider */
    private $pageUrlProvider;

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        $this->pageUrlProvider = Bootstrap::getObjectManager()->create(PageUrlProvider::class);
    }

    /**
     * @magentoDataFixture loadCmsPages
     * @dataProvider findDataProvider
     *
     * @param int $pageId
     * @param string $pageIdentifier
     * @param int $storeId
     */
    public function testLoadPageUrl(int $pageId, string $pageIdentifier, int $storeId)
    {
        $cmsUrls = $this->pageUrlProvider->execute($storeId, [$pageId]);

        $this->assertArrayHasKey($pageId, $cmsUrls);
        $this->assertContains($pageIdentifier, $cmsUrls[$pageId]);
    }

    /**
     * @return array
     */
    public function findDataProvider(): array
    {
        return [
            [100, 'page100', 1],
            [101, 'page_design_blank', 1],
        ];
    }

    /**
     * Load cms pages
     */
    public static function loadCmsPages() :void
    {
        include self::FIXTURE_DIRECTORY . 'pages.php';
    }
}
