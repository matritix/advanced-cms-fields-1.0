<?php
namespace Matritix\AdvancedCmsFields\Setup;

use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class Uninstall implements UninstallInterface
{


    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
		$connection = $installer->getConnection();
		
        $connection->dropColumn($installer->getTable('cms_page'), 'matritix_advancedform');
        $connection->dropColumn($installer->getTable('cms_block'), 'matritix_advancedform');
        $connection->dropColumn($installer->getTable('cms_block'), 'sort_order');
        $installer->endSetup();

    }//end uninstall()


}//end class