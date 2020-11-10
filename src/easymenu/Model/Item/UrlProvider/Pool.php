<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model\Item\UrlProvider;

use AMF\EasyMenu\Exception\UrlProviderNotExistException;
use AMF\EasyMenu\Model\Item\UrlProvider\Factory as ProviderFactory;
use AMF\EasyMenu\Model\Item\UrlProviderInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Return URL provider for given type
 */
class Pool
{
    /**
     * @var array
     */
    private $urlProviders = [];

    /**
     * @var ProviderFactory
     */
    private $providerFactory;

    /**
     * Pool constructor.
     *
     * @param Factory $providerFactory
     * @param array $urlProviders
     */
    public function __construct(ProviderFactory $providerFactory, array $urlProviders)
    {
        $this->providerFactory = $providerFactory;
        $this->urlProviders = $urlProviders;
    }

    /**
     * Retrieve url provider
     *
     * @param string $type
     *
     * @return UrlProviderInterface
     */
    public function get(string $type): UrlProviderInterface
    {
        if (isset($this->urlProviders[$type])) {
            return $this->providerFactory->create($this->urlProviders[$type]);
        }

        throw new UrlProviderNotExistException($type);
    }
}
