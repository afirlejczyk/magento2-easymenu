<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Ui\DataProvider\Item\Form\Modifier;

use AF\EasyMenu\Model\Item;
use AF\EasyMenu\Model\Locator\LocatorInterface;
use AF\EasyMenu\Ui\Component\Item\Form\ParentList as ParentOptions;
use Magento\Ui\Component\Form;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;

/**
 * ParentList Modifier
 */
class ParentList implements ModifierInterface
{
    /**
     * @var \AF\EasyMenu\Model\Locator\LocatorInterface
     */
    private $locator;

    /**
     * @var ParentOptions
     */
    private $parentOptions;

    /**
     * ParentList constructor.
     *
     * @param LocatorInterface $locator
     * @param ParentOptions $parentList
     */
    public function __construct(
        LocatorInterface $locator,
        ParentOptions $parentList
    ) {
        $this->locator = $locator;
        $this->parentOptions = $parentList;
    }

    /**
     * @inheritdoc
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * @inheritdoc
     */
    public function modifyMeta(array $meta)
    {
        $meta = $this->addParentField($meta);

        return $meta;
    }

    /**
     * @param array $meta
     *
     * @return array
     */
    private function addParentField(array $meta)
    {
        $menuItem = $this->locator->getMenuItem();
        $options = $this->parentOptions->toOptionArray();

        $isParentFieldVisible = false;

        if ($menuItem->getId() || !empty($options)) {
            $isParentFieldVisible = true;
        }

        $parentField = Item::PARENT_ID;

        $meta['general']['children'][$parentField]['arguments']['data'] = [
            'options' => $options,
            'config' => [
                'componentType' => Form\Field::NAME,
                'formElement' => Form\Element\Select::NAME,
                'dataType' => Form\Element\DataType\Text::NAME,
                'label' => __('Parent Item'),
                'component' => 'AF_EasyMenu/js/components/parent-list',
                'elementTmpl' => 'ui/grid/filters/elements/ui-select',
                'filterOptions' => true,
                'showCheckbox' => false,
                'disableLabel' => true,
                'multiple' => false,
                'levelsVisibility' => 1,
                'sortOrder' => 30,
                'visible' => $isParentFieldVisible,
                'validation' => ['required-entry' => true],
            ],
        ];

        if (!$menuItem->getId()) {
            $parentId = (int) $this->getParentId();
            $meta['general']['children'][$parentField]['arguments']['data']['config']['value'] = $parentId;
        }

        return $meta;
    }

    /**
     * Return Parent id from request
     *
     * @return int
     */
    private function getParentId()
    {
        return (int) $this->locator->getMenuItem()->getParentId();
    }
}
