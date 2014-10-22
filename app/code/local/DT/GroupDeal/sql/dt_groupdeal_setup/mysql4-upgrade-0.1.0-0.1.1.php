<?php
/**
 * Created by   : JetBrains PhpStorm.
 * @project     : dt-groupdeal
 * @author      : DUC THANG
 * @Date        : 10/18/14
 * @Time        : 2:26 PM
 * @copyright  Copyright (c) 2014
 *
 */
$installer = $this;

$installer->startSetup();

try {
    $installer->run("
       ALTER TABLE  `" . $this->getTable('sales/quote_item') . "` ADD  `is_deal` INT(1) NULL;
       ALTER TABLE  `" . $this->getTable('sales/quote_item') . "` ADD  `has_expired` INT(1) NULL;

       ALTER TABLE  `" . $this->getTable('sales/order_item') . "` ADD  `is_deal` INT(1) NULL;
       ALTER TABLE  `" . $this->getTable('sales/order_item') . "` ADD  `has_expired` INT(1) NULL;
");

} catch (Exception $e) {
}

$installer->endSetup();