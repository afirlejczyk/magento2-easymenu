<?php

declare(strict_types=1);

use AMF\EasyMenuAdminUi\Ui\Component\Item\Form\ParentList as ParentOptions;
use AMF\EasyMenuAdminUi\Model\Locator\LocatorInterface;
use AMF\EasyMenuAdminUi\Ui\DataProvider\Item\Form\Modifier\ParentList;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use Magento\Ui\Component\Form;
use PHPUnit\Framework\TestCase;

class ParentListTest extends TestCase
{
    /** @var ParentOptions|\PHPUnit\Framework\MockObject\MockObject */
    private $parentOptionsMock;
    /** @var \PHPUnit\Framework\MockObject\MockObject */
    private $locatorMock;
    /** @var \PHPUnit\Framework\MockObject\MockObject */
    private $itemMock;
    /** @var ParentList */
    private $parentListModifier;

    private $parentTreeArray = [[]];

    protected function setUp()
    {
        $this->parentOptionsMock = $this->createPartialMock(
            ParentOptions::class,
            ['toOptionArray']
        );

        $this->locatorMock = $this->getMockBuilder(LocatorInterface::class)->getMock();
        $this->itemMock = $this->getMockBuilder(ItemInterface::class)->getMock();
        $this->locatorMock->method('getMenuItem')->willReturn($this->itemMock);

        $this->parentListModifier = new ParentList(
            $this->locatorMock,
            $this->parentOptionsMock
        );
    }

    public function testModifyDataWillNotChangeData()
    {
        $data = [
            'meta' => 1,
        ];

        self::assertEquals(
            $data,
            $this->parentListModifier->modifyData($data)
        );
    }

    public function testAddInvisibleParentField()
    {
        $parentId = 99;

        $this->parentOptionsMock->method('toOptionArray')->willReturn([]);
        $this->itemMock->method('getId')->willReturn(null);
        $this->itemMock->method('getParentId')->willReturn($parentId);

        $expectedResult = [
            'general' => [
                'children' => [
                    'parent_id' => [
                        'arguments' => [
                            'data' => [
                                'options' => [],
                                'config' => [
                                    'componentType' => Form\Field::NAME,
                                    'formElement' => Form\Element\Select::NAME,
                                    'dataType' => Form\Element\DataType\Text::NAME,
                                    'label' => __('Parent Item'),
                                    'component' => 'AMF_EasyMenuAdminUi/js/components/parent-list',
                                    'elementTmpl' => 'ui/grid/filters/elements/ui-select',
                                    'filterOptions' => true,
                                    'showCheckbox' => false,
                                    'disableLabel' => true,
                                    'multiple' => false,
                                    'levelsVisibility' => 1,
                                    'sortOrder' => 30,
                                    'visible' => false,
                                    'validation' => ['required-entry' => true],
                                    'value' => $parentId,
                                ],
                            ]
                        ]
                    ]
                ]
            ]
        ];

        self::assertEquals(
            $expectedResult,
            $this->parentListModifier->modifyMeta([])
        );
    }

    public function testAddParentFieldWithDefaultValue()
    {
        $parentId = 99;
        $this->itemMock->method('getParentId')->willReturn($parentId);
        $this->parentOptionsMock->method('toOptionArray')->willReturn($this->parentTreeArray);

        $expectedResult = [
            'general' => [
                'children' => [
                    'parent_id' => [
                        'arguments' => [
                            'data' => [
                                'options' => $this->parentTreeArray,
                                'config' => [
                                    'componentType' => Form\Field::NAME,
                                    'formElement' => Form\Element\Select::NAME,
                                    'dataType' => Form\Element\DataType\Text::NAME,
                                    'label' => __('Parent Item'),
                                    'component' => 'AMF_EasyMenuAdminUi/js/components/parent-list',
                                    'elementTmpl' => 'ui/grid/filters/elements/ui-select',
                                    'filterOptions' => true,
                                    'showCheckbox' => false,
                                    'disableLabel' => true,
                                    'multiple' => false,
                                    'levelsVisibility' => 1,
                                    'sortOrder' => 30,
                                    'visible' => true,
                                    'validation' => ['required-entry' => true],
                                    'value' => $parentId,
                                ],
                            ]
                        ]
                    ]
                ]
            ]
        ];

        self::assertEquals(
            $expectedResult,
            $this->parentListModifier->modifyMeta([])
        );
    }

    public function testAddParentFieldWithoutDefaultValue()
    {
        $this->itemMock->method('getId')->willReturn(1);
        $this->parentOptionsMock->method('toOptionArray')->willReturn($this->parentTreeArray);

        $expectedResult = [
            'general' => [
                'children' => [
                    'parent_id' => [
                        'arguments' => [
                            'data' => [
                                'options' => $this->parentTreeArray,
                                'config' => [
                                    'componentType' => Form\Field::NAME,
                                    'formElement' => Form\Element\Select::NAME,
                                    'dataType' => Form\Element\DataType\Text::NAME,
                                    'label' => __('Parent Item'),
                                    'component' => 'AMF_EasyMenuAdminUi/js/components/parent-list',
                                    'elementTmpl' => 'ui/grid/filters/elements/ui-select',
                                    'filterOptions' => true,
                                    'showCheckbox' => false,
                                    'disableLabel' => true,
                                    'multiple' => false,
                                    'levelsVisibility' => 1,
                                    'sortOrder' => 30,
                                    'visible' => true,
                                    'validation' => ['required-entry' => true],
                                ],
                            ]
                        ]
                    ]
                ]
            ]
        ];

        self::assertEquals(
            $expectedResult,
            $this->parentListModifier->modifyMeta([])
        );
    }
}
