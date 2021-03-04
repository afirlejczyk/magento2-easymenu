<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model\Item\UrlProvider;

use AMF\EasyMenu\Exception\UrlProviderNotExistException;
use AMF\EasyMenu\Model\Item\UrlProvider\Factory as ProviderFactory;
use AMF\EasyMenu\Model\Item\UrlProviderInterface;

/**
 * Return URL provider for given type
 */
class Pool
{
    /**
     * @var array<UrlProviderInterface>
     */
    private $urlProviders;

    /**
     * @var ProviderFactory
     */
    private $providerFactory;

    /**
     * Pool constructor.
     *
     * @param Factory $providerFactory
     * @param array<UrlProviderInterface> $urlProviders
     */
    public function __construct(ProviderFactory $providerFactory, array $urlProviders)
    {
        $this->providerFactory = $providerFactory;
        $this->urlProviders = $urlProviders;
    }

    /**
     * @throws UrlProviderNotExistException
     */
    public function get(string $type): UrlProviderInterface
    {
        if (isset($this->urlProviders[$type])) {
            return $this->providerFactory->create($this->urlProviders[$type]);
        }

        throw new UrlProviderNotExistException($type);
    }
}
