<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model\Item\UrlProvider;

use AMF\EasyMenu\Model\Item\UrlProviderInterface;
use AMF\EasyMenuApi\Api\Data\ItemInterface;
use Magento\Framework\UrlInterface;

/**
 * Retrieve url for external links
 */
class ExternalUrlProvider implements UrlProviderInterface
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * CategoryUrlProvider constructor.
     *
     * @param UrlInterface $urlBuilder
     */
    public function __construct(UrlInterface $urlBuilder)
    {
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @inheritdoc
     */
    public function loadAll(int $storeId, ItemInterface ...$items)
    {
        $urls = [];

        foreach ($items as $item) {
            $urls[$item->getId()] = $this->buildCustomUrlLink($item->getValue());
        }

        return $urls;
    }

    /**
     * Build custom url link
     *
     * @param string $value
     *
     * @return string
     */
    private function buildCustomUrlLink(string $value): string
    {
        if (preg_match('@^https?://@', $value)) {
            return $value;
        }

        $url = $this->urlBuilder->getBaseUrl();
        $url .= $value;

        return rtrim($url, '/');
    }
}
