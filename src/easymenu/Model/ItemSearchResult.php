<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model;

use AMF\EasyMenuApi\Api\Data\ItemSearchResultInterface;
use Magento\Framework\Api\Search\SearchResult;

/**
 * {@inheritDoc}
 */
class ItemSearchResult extends SearchResult implements ItemSearchResultInterface
{
}
