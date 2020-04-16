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
     * @var UrlBuilderInterface
     */
    private $urlBuilder;

    /**
     * @var
     */
    private $urlById;

    /**
     * UrlResolver constructor.
     *
     * @param UrlBuilderInterface $urlBuilder
     */
    public function __construct(UrlBuilderInterface $urlBuilder)
    {
        $this->urlBuilder = $urlBuilder;
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
            $this->urlById = $this->urlBuilder->getUrlsForActiveItems($item->getStoreId());
        }

        return $this->urlById[$item->getId()];
    }
}
