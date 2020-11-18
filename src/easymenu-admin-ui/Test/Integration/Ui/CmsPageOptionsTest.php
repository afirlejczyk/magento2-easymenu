<?php

namespace AMF\EasyMenuAdminUi\Test\Integration\Ui;

use AMF\EasyMenuAdminUi\Ui\Component\Item\Form\CmsPageOptions;
use Magento\Store\Api\Data\StoreInterface;
use PHPUnit\Framework\TestCase;
use Magento\TestFramework\Helper\Bootstrap;
use AMF\EasyMenuAdminUi\Registry\CurrentStore as StoreRegistry;

/**
 * Unit test for CmsPageOptions class
 */
class CmsPageOptionsTest extends TestCase
{
    const FIXTURE_DIRECTORY = __DIR__ . '/../_files/';

    /** @var CmsPageOptions  */
    private $cmsPageOptions;

    /** @var StoreRegistry */
    private $storeRegistry;

    protected function setUp()
    {
        $this->cmsPageOptions = Bootstrap::getObjectManager()->create(CmsPageOptions::class);
        $this->storeRegistry = Bootstrap::getObjectManager()->get(StoreRegistry::class);
    }

    /**
     * @magentoDataFixture loadCmsPages
     */
    public function testGetAllCmsPageOptionsForCurrentStore()
    {
        $store = $this->createMock(StoreInterface::class);
        $store->method('getId')->willReturn('1');
        $this->storeRegistry->set($store);

        $result = $this->cmsPageOptions->toOptionArray();

        $titles = array_column($result, 'label');

        self::assertContains('page100', $titles);
        self::assertContains('page_design_blank', $titles);
    }

    /**
     * Load cms pages
     */
    public static function loadCmsPages() :void
    {
        include self::FIXTURE_DIRECTORY . 'pages.php';
    }
}
