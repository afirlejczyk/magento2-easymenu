<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model\Item;

use AMF\EasyMenuApi\Api\Data\ItemInterface;

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
     * @var array<string>
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
        if ($this->urlById === null) {
            /** @var UrlBuilderInterface $urlBuilder */
            $urlBuilder = $this->urlBuilderFactory->create(['storeId' => $item->getStoreId()]);
            $this->urlById = $urlBuilder->getUrlsForActiveItems();
        }

        return $this->urlById[$item->getId()];
    }
}
