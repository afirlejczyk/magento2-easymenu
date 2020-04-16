<?php

declare(strict_types=1);

namespace AMF\EasyMenu\Block\Html;

use AMF\EasyMenu\Model\Item;
use AMF\EasyMenuApi\Model\MenuTreeInterface;
use Magento\Catalog\Model\Category;
use Magento\Cms\Model\Page;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Block to render menu
 */
class Topmenu extends Template implements IdentityInterface
{
    /**
     * @var MenuTreeInterface
     */
    private $tree;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Default identities
     *
     * @var array
     */
    private $identities = [
        Category::CACHE_TAG,
        Page::CACHE_TAG,
    ];

    /**
     * Topmenu constructor.
     *
     * @param Context $context
     * @param MenuTreeInterface $tree
     * @param array $data
     */
    public function __construct(
        Context $context,
        MenuTreeInterface $tree,
        array $data = []
    ) {
        $this->storeManager = $context->getStoreManager();
        $this->tree = $tree;
        parent::__construct($context, $data);
    }

    /**
     * Add identity
     *
     * @param string $identity
     *
     * @return void
     */
    public function addIdentity(string $identity): void
    {
        if (! in_array($identity, $this->identities)) {
            $this->identities[] = $identity;
        }
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities(): array
    {
        return $this->identities;
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheKeyInfo()
    {
        $cacheKey = parent::getCacheKeyInfo();
        $cacheKey[] = 'TOP_NAVIGATION';

        return $cacheKey;
    }

    /**
     * {@inheritdoc}
     */
    public function getHtml()
    {
        /** @var \AMF\EasyMenu\ViewModel\MenuRenderer  $viewModel */
        $viewModel = $this->getData('menuRenderViewModel');

        return $viewModel->render($this->getMenu());
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        parent::_construct();

        $this->addIdentity(
            sprintf('%s_%s', Item::CACHE_TAG_STORE, $this->getStoreId())
        );
        $this->addData(
            [
                'cache_lifetime' => 86400,
                'cache_tags' => $this->getIdentities(),
            ]
        );
    }

    /**
     * Retrieve Menu Edit
     *
     * @return Node
     *
     * @throws NoSuchEntityException
     */
    private function getMenu(): Node
    {
        return $this->tree->getMenuTree($this->getStoreId());
    }

    /**
     * Retrieve current store ID
     *
     * @return int
     *
     * @throws NoSuchEntityException
     */
    private function getStoreId(): int
    {
        return (int) $this->storeManager->getStore()->getId();
    }
}
