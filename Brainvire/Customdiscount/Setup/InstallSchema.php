<?php

namespace Brainvire\Customdiscount\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.0') < 0){

		$installer->run('create table brainvire_customdiscount(brainvire_customdiscount_id int not null auto_increment, 
            product_sku varchar(100),discount_type varchar(100),
            discount_amount varchar(100),status varchar(100), 
            primary key(brainvire_customdiscount_id))');
		}

        $installer->endSetup();

    }
}