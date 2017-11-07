<?php
/**
 * @package AF\EasyMenu
 * @author Agata Firlejczyk
 * @copyright Copyright (c) 2017 Agata Firlejczyk
 * @license See LICENSE for license details.
 */

namespace AF\EasyMenu\Setup;

use AF\EasyMenu\Api\Data\ItemInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * class InstallSchema
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $table = $installer->getConnection()
            ->newTable($installer->getTable($this->getTableName()))
            ->addColumn(
                ItemInterface::ITEM_ID,
                Table::TYPE_INTEGER,
                11,
                [
                    'primary' => true,
                    'auto_increment' => true,
                    'nullable' => 'false',
                ],
                'ID'
            )->addColumn(
                ItemInterface::NAME,
                Table::TYPE_TEXT,
                50,
                ['nullable' => false],
                'title'
            )->addColumn(
                ItemInterface::PARENT_ID,
                Table::TYPE_INTEGER,
                11,
                [
                    'nullable' => false,
                    'default' => 0,
                ],
                'Parent ID'
            )->addColumn(
                ItemInterface::TYPE,
                Table::TYPE_INTEGER,
                11,
                ['nullable' => false],
                'Link Type'
            )->addColumn(
                ItemInterface::VALUE,
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Item Value'
            )->addColumn(
                ItemInterface::PRIORITY,
                Table::TYPE_INTEGER,
                11,
                ['nullable' => false],
                'Priority'
            )->addColumn(
                ItemInterface::STORE_ID,
                Table::TYPE_INTEGER,
                11,
                [
                    'nullable' => true,
                    'comment' => 'Store Id',
                ],
                'store'
            )->addColumn(
                ItemInterface::OPEN_LINK_IN_NEW_WINDOW,
                Table::TYPE_SMALLINT,
                null,
                [
                    'nullable' => false,
                    'default' => '0',
                ],
                'Open link in new window/tab'
            )
            ->addColumn(
                ItemInterface::IS_ACTIVE,
                Table::TYPE_SMALLINT,
                null,
                [
                    'nullable' => false,
                    'default' => '1',
                ],
                'Is Menu Item Active'
            )
            ->setComment('EasyMenu table')
            ->addIndex(
                $setup->getConnection()->getIndexName(
                    $this->getTableName(),
                    ItemInterface::STORE_ID
                ),
                [ItemInterface::STORE_ID]
            );

        $installer->getConnection()->createTable($table);
    }

    /**
     * @return string
     */
    private function getTableName()
    {
        return \AF\EasyMenu\Model\ResourceModel\Item::TABLE_NAME;
    }
}
