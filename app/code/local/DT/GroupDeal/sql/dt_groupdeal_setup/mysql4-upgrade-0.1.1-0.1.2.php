<?php
/**
 * Created by JetBrains PhpStorm.
 * User: DUCTHANG
 * Date: 10/21/14
 * Time: 10:59 PM
 * To change this template use File | Settings | File Templates.
 */
$installer = $this;

$installer->startSetup();
$table = $installer->getConnection()
    ->newTable($installer->getTable('dt_groupdeal/order'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    'identity' => true,
    'unsigned' => true,
    'nullable' => false,
    'primary' => true,
), 'Entity Id')
    ->addColumn('group_deal_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'Group Deal Id')
    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'Customer Id')
    ->addColumn('billing_firstname', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Firstname')
    ->addColumn('billing_lastname', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Lastname')
    ->addColumn('billing_company', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Company')
    ->addColumn('billing_email', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Email')
    ->addColumn('billing_street', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Street')
    ->addColumn('billing_city', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'City')
    ->addColumn('billing_region_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'Region Id')
    ->addColumn('billing_region', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Region')
    ->addColumn('billing_postcode', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Postcode')
    ->addColumn('billing_country_id', Varien_Db_Ddl_Table::TYPE_TEXT, 2, array(), 'Country Id')
    ->addColumn('billing_telephone', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Telephone')
    ->addColumn('billing_fax', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Fax')

    ->addColumn('shipping_firstname', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Firstname')
    ->addColumn('shipping_lastname', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Lastname')
    ->addColumn('shipping_company', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Company')
    ->addColumn('shipping_email', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Email')
    ->addColumn('shipping_street', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Street')
    ->addColumn('shipping_city', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'City')
    ->addColumn('shipping_region_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'Region Id')
    ->addColumn('shipping_region', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Region')
    ->addColumn('shipping_postcode', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Postcode')
    ->addColumn('shipping_country_id', Varien_Db_Ddl_Table::TYPE_TEXT, 2, array(), 'Country Id')
    ->addColumn('shipping_telephone', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Telephone')
    ->addColumn('shipping_fax', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(), 'Fax')

    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(), 'Created At')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(), 'Updated At');
$installer->getConnection()->createTable($table);