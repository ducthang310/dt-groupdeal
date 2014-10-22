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
class DT_GroupDeal_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function checkDealExpired($product) {
        if ($product && $product->getId()) {
            if (Mage::registry('dt_deal_' . $product->getId())) {
                return 1;
            }
            // check deal for $product
            $_col = Mage::getModel('dt_groupdeal/deal')->getCollection();
            $_col->addFieldToFilter('product_id', $product->getId());
            $_deal = $_col->getFirstItem();
            if ($_deal->getId()) {
                //TODO: Check deal with date
                Mage::register('dt_deal_' . $product->getId(), $_deal);
                return 1;
            }
        }
        return 0;
    }
}