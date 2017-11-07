<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Model\Item;

/**
 * Interface UrlBuilderInterface
 */
interface UrlBuilderInterface
{

    /**
     * @param int $storeId
     *
     * @return array
     */
    public function getUrlForActiveMenuItems($storeId);
}
