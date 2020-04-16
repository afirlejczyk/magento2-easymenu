<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Model\Item;

use AMF\EasyMenuApi\Api\Data\ItemInterface;

/**
 * @inheritdoc
 */
class ConvertToArray implements \AMF\EasyMenuApi\Model\Item\ConvertToArrayInterface
{
    /**
     * @var UrlResolver
     */
    private $urlResolver;

    /**
     * ConvertToArray constructor.
     *
     * @param UrlResolver $resolver
     */
    public function __construct(UrlResolver $resolver)
    {
        $this->urlResolver = $resolver;
    }

    /**
     * @inheritdoc
     *
     * @param ItemInterface $item
     *
     * @return array
     */
    public function execute(ItemInterface $item): array
    {
        return [
            'url' => $this->urlResolver->getUrl($item),
            'id' => $item->getId(),
            'name' => $item->getName(),
        ];
    }
}
