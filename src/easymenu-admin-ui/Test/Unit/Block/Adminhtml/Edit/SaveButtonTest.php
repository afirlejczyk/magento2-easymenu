<?php

namespace AMF\EasyMenuAdminUi\Test\Unit\Block\Adminhtml\Edit;

use AMF\EasyMenuAdminUi\Block\Adminhtml\Edit\SaveButton;
use PHPUnit\Framework\TestCase;

class SaveButtonTest extends TestCase
{
    public function testGetButtonData()
    {
        $saveButton = new SaveButton();

        self::assertEquals(
            [
                'label' => __('Save Item'),
                'class' => 'save primary',
                'data_attribute' => [
                    'mage-init' => ['button' => ['event' => 'save']],
                    'form-role' => 'save',
                ],
                'sort_order' => 90,
            ],
            $saveButton->getButtonData()
        );
    }
}
