<?php

namespace AMF\EasyMenuGraphql\Model\Resolver;

use AMF\EasyMenuGraphql\Model\DataProvider\MenuTree as MenuTreeDataProvider;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * MenuTree resolver
 */
class MenuTree implements ResolverInterface
{
    /**
     * @var MenuTreeDataProvider
     */
    private $menuTreeProvider;

    /**
     * EasyMenu constructor.
     * @param MenuTreeDataProvider $menuTree
     */
    public function __construct(MenuTreeDataProvider $menuTree)
    {
        $this->menuTreeProvider = $menuTree;
    }

    /**
     * @param Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     *
     * @return \array[][]|\Magento\Framework\GraphQl\Query\Resolver\Value|mixed
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $store = $context->getExtensionAttributes()->getStore();

        return ['items' => $this->menuTreeProvider->getDataByStoreId((int)$store->getId())];
    }
}
