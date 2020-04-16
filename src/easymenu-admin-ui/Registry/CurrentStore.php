<?php

declare(strict_types=1);

namespace AMF\EasyMenuAdminUi\Registry;

use Magento\Store\Api\Data\StoreInterface;

/**
 * Responsible for register and retrieve current store
 */
class CurrentStore
{
    /**
     * @var StoreInterface
     */
    private $store;

    /**
     * Set Current Store
     *
     * @param StoreInterface $store
     */
    public function set(StoreInterface $store): void
    {
        $this->store = $store;
    }

    /**
     * Get Current Store
     *
     * @return StoreInterface|null
     */
    public function get(): ?StoreInterface
    {
        return $this->store;
    }
}
