<?php

namespace AMF\EasyMenuAdminUi\Test\Unit\Ui\Component\Item\Form;

use AMF\EasyMenuAdminUi\Ui\Component\Item\Form\LinkType;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for LinkType class
 */
class LinkTypeTest extends TestCase
{
    /** @var LinkType */
    private $linkType;

    protected function setUp()
    {
        $this->linkType = new LinkType();
    }


    public function testToOptionArray()
    {
        $result = [
            [
                'value' => ItemInterface::TYPE_CATEGORY,
                'label' => __('Category')
            ],
            [
                'value' => ItemInterface::TYPE_CMS_PAGE,
                'label' => __('CMS Page')
            ],
            [
                'value' => ItemInterface::TYPE_CUSTOM_LINK,
                'label' => __('Custom Link')
            ],
        ];

        $this->assertEquals(
            $result,
            $this->linkType->toOptionArray()
        );
    }
}
