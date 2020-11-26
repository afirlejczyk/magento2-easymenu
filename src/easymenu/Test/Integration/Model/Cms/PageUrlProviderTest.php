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
     */
    public function testLoadMultiplePages()
    {
        $pages = [
            ['id' => 100, 'identifier' => 'page100'],
            ['id' => 101, 'identifier' =>  'page_design_blank'],
        ];

        $cmsUrls = $this->pageUrlProvider->execute(1, array_column($pages, 'id'));

        foreach ($pages as $page) {
            ['id' => $pageId, 'identifier' => $pageIdentifier] = $page;
            self::assertArrayHasKey($pageId, $cmsUrls);
            self::assertContains($pageIdentifier, $cmsUrls[$pageId]);
        }
    }

    /**
     * Load cms pages
     */
    public static function loadCmsPages() :void
    {
        include self::FIXTURE_DIRECTORY . 'pages.php';
    }
}
