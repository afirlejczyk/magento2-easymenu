<?php

use Magento\TestFramework\Helper\Bootstrap;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewrite as UrlRewriteResource;
use Magento\UrlRewrite\Model\UrlRewrite;

$objectManager = Bootstrap::getObjectManager();

$rewritesData = [
    [
        'category-url', 3, 1,
    ],
    [
        'string-category', 4, 2
    ]
];

$rewriteResource = $objectManager->create(UrlRewriteResource::class);
foreach ($rewritesData as $rewriteData) {
    [$requestPath, $categoryId, $storeId] = $rewriteData;

    /** @var UrlRewrite $rewrite */
    $rewrite = $objectManager->create(UrlRewrite::class);
    $rewrite->setEntityType('category')
        ->setRequestPath($requestPath)
        ->setStoreId($storeId)
        ->setEntityId($categoryId)
        ->setRedirectType(0);
    $rewriteResource->save($rewrite);
}
