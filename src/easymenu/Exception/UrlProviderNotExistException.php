<?php

namespace AMF\EasyMenu\Exception;

use Magento\Framework\Exception\LocalizedException;

class UrlProviderNotExistException extends LocalizedException
{
    /**
     * UrlProviderNotExistException constructor.
     * @param string $type
     */
    public function __construct(string $type)
    {
        parent::__construct(__('Url provider for %type wasn\'t found', ['type' => $type]));
    }
}
