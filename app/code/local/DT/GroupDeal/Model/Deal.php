<?php
/**
 * Created by   : JetBrains PhpStorm.
 * @project     : dt-groupdeal
 * @author      : DUC THANG
 * @Date        : 10/7/14
 * @Time        : 10:59 PM
 * @copyright  Copyright (c) 2014
 *
 */
class DT_GroupDeal_Model_Deal extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('dt_groupdeal/deal');
    }
}