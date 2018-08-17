<?php

namespace Matritix\AdvancedCmsFields\Setup;



use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallSchema implements InstallSchemaInterface
{


    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $connection = $installer->getConnection();

        $connection->addColumn($installer->getTable('cms_page'), 'matritix_advancedform', ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 'nullable' => false, 'comment' => 'All data']);
        $connection->addColumn($installer->getTable('cms_block'), 'matritix_advancedform', ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 'nullable' => false, 'comment' => 'All data']);
        $connection->addColumn($installer->getTable('cms_block'), 'sort_order', ['type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT, 'nullable' => false, 'comment' => 'Sort Block']);

        $tableName  = $installer->getTable('cms_page');
        $columnName = 'sort_order';

        if ($connection->tableColumnExists($tableName, $columnName) === false) {
            $connection->addColumn(
                $tableName,
                $columnName,
                [
                    'type'     => Varien_Db_Ddl_Table::TYPE_SMALLINT,
                    'nullable' => false,
                    'comment'  => 'sort order',
                ]
            );
        }

        $installer->endSetup();

    }//end install()


}//end class