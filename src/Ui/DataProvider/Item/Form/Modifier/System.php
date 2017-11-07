<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Ui\DataProvider\Item\Form\Modifier;

use AF\EasyMenu\Model\Locator\LocatorInterface;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Magento\Framework\UrlInterface;

/**
 * System Modifier
 */
class System implements ModifierInterface
{
    const KEY_SUBMIT_URL = 'submit_url';

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var \AF\EasyMenu\Model\Locator\LocatorInterface
     */
    private $locator;

    /**
     * @param LocatorInterface $locator
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        LocatorInterface $locator,
        UrlInterface $urlBuilder
    ) {
        $this->locator = $locator;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @inheritdoc
     */
    public function modifyData(array $data)
    {
        $submitUrl = $this->urlBuilder->getUrl(
            'easymenu/item/save',
            ['store' => $this->getStoreId()]
        );

        return array_replace_recursive(
            $data,
            [
                'config' => [
                    self::KEY_SUBMIT_URL => $submitUrl,
                ],
            ]
        );
    }

    /**
     * @return int
     */
    private function getStoreId()
    {
        return $this->locator->getStore()->getId();
    }

    /**
     * @inheritdoc
     */
    public function modifyMeta(array $meta)
    {
        return $meta;
    }
}
