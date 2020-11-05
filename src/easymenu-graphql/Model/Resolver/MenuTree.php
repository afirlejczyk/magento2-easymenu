<?php

namespace AMF\EasyMenuGraphql\Model\Resolver;

use AMF\EasyMenuGraphql\Model\DataProvider\MenuTree as MenuTreeDataProvider;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
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
     * @throws GraphQlInputException
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $storeId = $this->getStoreId($args);

        return ['items' => $this->menuTreeProvider->getDataByStoreId($storeId)];
    }

    /**
     * @param array $args
     * @return int
     * @throws GraphQlInputException
     */
    public function getStoreId(array $args): int
    {
        if (!isset($args['store_id'])) {
            throw new GraphQlInputException(__('store_id should be specified'));
        }

        return $args['store_id'];
    }
}
