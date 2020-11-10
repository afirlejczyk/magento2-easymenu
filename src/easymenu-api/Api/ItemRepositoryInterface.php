<?php
/**
 * @package AMF\EasyMenuApi
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */
declare(strict_types=1);

namespace AMF\EasyMenuApi\Api;

use AMF\EasyMenuApi\Api\Data\ItemInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * In Magento 2 Repository considered as an implementation of Facade pattern which provides a simplified interface
 * to a larger body of code responsible for Domain Entity management
 *
 * The main intention is to make API more readable and reduce dependencies of business logic code on the inner workings
 * of a module, since most code uses the facade, thus allowing more flexibility in developing the system
 *
 * Along with this such approach helps to segregate two responsibilities:
 * 1. Repository now could be considered as an API - Interface for usage (calling) in the business logic
 * 2. Separate class-commands to which Repository proxies initial call (like, Get Save GetList Delete) could be
 *    considered as SPI - Interfaces that you should extend and implement to customize current behaviour
 *
 * Used fully qualified namespaces in annotations for proper work of WebApi request parser
 *
 * @api
 */
interface ItemRepositoryInterface
{
    /**
     * Save menu item
     *
     * @param \AMF\EasyMenuApi\Api\Data\ItemInterface $item
     *
     * @return int
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(Data\ItemInterface $item): int;

    /**
     * Get menu item
     *
     * @param int $itemId
     *
     * @return \AMF\EasyMenuApi\Api\Data\ItemInterface
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($itemId): ItemInterface;

    /**
     * Delete menu item
     *
     * @param \AMF\EasyMenuApi\Api\Data\ItemInterface $item
     *
     * @return void
     *
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(Data\ItemInterface $item): void;

    /**
     * Find Items by given SearchCriteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \AMF\EasyMenuApi\Api\Data\ItemSearchResultInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
