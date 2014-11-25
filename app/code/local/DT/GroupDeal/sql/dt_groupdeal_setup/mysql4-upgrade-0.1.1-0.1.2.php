<?php

/* @var $installer Mage_Catalog_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

try {
    $installer->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'deal_id', array(
        'group' => 'General',
        'input' => 'text',
        'type' => 'int',
        'label' => 'Deal Id',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'backend' => '',
        'visible' => false,
        'required' => false,
        'user_defined' => true,
        'searchable' => false,
        'filterable' => false,
        'comparable' => false,
        'default' => NULL,
        'visible_on_front' => true,
        'used_in_product_listing' => true,
        'visible_in_advanced_search' => false,
        'is_html_allowed_on_front' => '0',
        'is_configurable' => true,
        'apply_to' => 'simple,grouped,configurable'
    ));
} catch (Exception $e) {
}
$installer->endSetup();