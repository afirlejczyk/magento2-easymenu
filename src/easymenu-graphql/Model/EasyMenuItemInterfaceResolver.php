<?php

namespace AMF\EasyMenuGraphql\Model;

use Magento\Framework\GraphQl\Exception\GraphQlInputException;

/**
 * {@inheritdoc}
 */
class EasyMenuItemInterfaceResolver implements \Magento\Framework\GraphQl\Query\Resolver\TypeResolverInterface
{
    /**
     * {@inheritdoc}
     * @throws GraphQlInputException
     */
    public function resolveType(array $data) : string
    {
        return 'EasyMenuTree';
    }
}
