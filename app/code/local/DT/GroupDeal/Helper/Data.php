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
class DT_GroupDeal_Helper_Data extends Mage_Core_Helper_Abstract {
    public function checkDeal($product) {
        // check deal for $product
        $ids = array(16, 166, 165);
        if (in_array($product->getId(), $ids)) {
            return 1;
        }
        return 0;
    }
}