<?php
/**
 * Created by JetBrains PhpStorm.
 * User: DUCTHANG
 * Date: 10/21/14
 * Time: 11:16 PM
 * To change this template use File | Settings | File Templates.
 */
class DT_GroupDeal_Model_Order extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('dt_groupdeal/order');
    }

}
