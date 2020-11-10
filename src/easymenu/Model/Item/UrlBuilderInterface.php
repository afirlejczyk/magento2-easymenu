<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model\Item;

/**
 * Interface ItemUrlBuilderInterface
 */
interface UrlBuilderInterface
{
    /**
     * Retrieve url for active menu items
     *
     * @return array
     */
    public function getUrlsForActiveItems(): array;
}
