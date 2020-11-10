<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model\Item;

use AMF\EasyMenuApi\Api\Data\ItemInterface;
use AMF\EasyMenu\Model\Item\UrlBuilderInterfaceFactory;

/**
 * Resolve url for specific Menu Item
 */
class UrlResolver
{
    /**
     * @var UrlBuilderInterfaceFactory
     */
    private $urlBuilderFactory;

    /**
     * @var array
     */
    private $urlById;

    /**
     * UrlResolver constructor.
     *
     * @param UrlBuilderInterfaceFactory $urlBuilder
     */
    public function __construct(UrlBuilderInterfaceFactory $urlBuilder)
    {
        $this->urlBuilderFactory = $urlBuilder;
    }

    /**
     * Retrieve menu item url
     *
     * @param ItemInterface $item
     *
     * @return string
     */
    public function getUrl(ItemInterface $item): string
    {
        if (null === $this->urlById) {
            /** @var UrlBuilderInterface $urlBuilder */
            $urlBuilder = $this->urlBuilderFactory->create(['storeId' => $item->getStoreId()]);
            $this->urlById = $urlBuilder->getUrlsForActiveItems();
        }

        return $this->urlById[$item->getId()];
    }
}
