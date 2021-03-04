<?php

use Magento\Framework\Exception\LocalizedException;

class NoSuchStoreException extends LocalizedException
{
    public function __construct(int $storeId)
    {
        parent::__construct(__('No such store with ID = %1', $storeId));
    }
}
