<?php
/**
 * Created by   : JetBrains PhpStorm.
 * @project     : dt-groupdeal
 * @author      : DUC THANG
 * @Date        : 10/7/14
 * @Time        : 8:41 PM
 * @copyright  Copyright (c) 2014
 *
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('dt_group_deal')};
CREATE TABLE {$this->getTable('dt_group_deal')} (
  `group_deal_id` int(11) unsigned NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `tier_id` int(11) NOT NULL,
  `group_deal_name` varchar(255) NOT NULL,
  `is_active` boolean NOT NULL default 1,
  `deal_from_date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `deal_to_date` timestamp NOT NULL default '0000-00-00 00:00:00',
  `deal_description` text NOT NULL,
  `order_ids` text NULL,
  `current_price` decimal(12,4) NULL default 0,
  `current_qty_ordered` int(11) NULL default 0,
  `is_calculated` int(1) NULL,
  `created_at` timestamp NOT NULL default '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY (`group_deal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('dt_deal_tier_price')};
CREATE TABLE {$this->getTable('dt_deal_tier_price')} (
  `tier_id` int(11) unsigned NOT NULL auto_increment,
  `tier_name` varchar(255) NOT NULL,
  `tier_price` text NOT NULL,
  PRIMARY KEY (`tier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
$installer->endSetup();