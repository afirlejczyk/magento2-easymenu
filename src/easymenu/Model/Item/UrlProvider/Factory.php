<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model\Item\UrlProvider;

use AMF\EasyMenu\Model\Item\UrlProviderInterface;
use Magento\Framework\ObjectManagerInterface;

/**
 * Class Responsible to create UrlProviderInterface class
 */
class Factory
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Factory constructor.
     *
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Get/create UrlProviderInterface class
     *
     * @param string $className
     *
     * @return UrlProviderInterface
     */
    public function create(string $className): UrlProviderInterface
    {
        $provider = $this->objectManager->get($className);

        if (! $provider instanceof UrlProviderInterface) {
            throw new \InvalidArgumentException(
                sprintf('%s doesnt\t implement %s', $className, UrlProviderInterface::class)
            );
        }

        return $provider;
    }
}
