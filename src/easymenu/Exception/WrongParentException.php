<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Exception;

use Magento\Framework\Exception\LocalizedException;

class WrongParentException extends LocalizedException
{
    public function __construct()
    {
        parent::__construct(
            __('You cannot select yourself as parent. Please select different parent item.')
        );
    }
}
