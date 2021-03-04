<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model;

use AMF\EasyMenuApi\Api\Data\ItemSearchResultInterface;
use Magento\Framework\Api\SearchResults;

class ItemSearchResult extends SearchResults implements ItemSearchResultInterface
{
}
