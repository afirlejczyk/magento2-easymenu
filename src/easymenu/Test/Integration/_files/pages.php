<?php

$pagesData = [
    [
        100,
        'page100',
        1
    ],
    [
        101,
        'page_design_blank',
        1
    ],
];

foreach ($pagesData as $page) {
    [$pageId, $identifier, $storeId] = $page;

    /** @var $page \Magento\Cms\Model\Page */
    $page = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(\Magento\Cms\Model\Page::class);
    $page->setTitle('Cms Page 100')
        ->setIdentifier($identifier)
        ->setStoreId($storeId)
        ->setId($pageId)
        ->setIsActive(1)
        ->setContent('<h1>Cms Page 100 Title</h1>')
        ->setContentHeading('<h2>Cms Page 100 Title</h2>')
        ->setMetaTitle('Cms Meta title for page100')
        ->setMetaKeywords('Cms Meta Keywords for page100')
        ->setMetaDescription('Cms Meta Description for page100')
        ->setPageLayout('1column')
        ->save();

}
