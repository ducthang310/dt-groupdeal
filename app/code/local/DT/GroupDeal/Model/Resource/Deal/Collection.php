<?php
/**
 * Created by   : JetBrains PhpStorm.
 * @project     : dt-groupdeal
 * @author      : DUC THANG
 * @Date        : 10/7/14
 * @Time        : 11:09 PM
 * @copyright  Copyright (c) 2014
 *
 */
class DT_GroupDeal_Model_Resource_Deal_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('dt_groupdeal/deal');
    }
}