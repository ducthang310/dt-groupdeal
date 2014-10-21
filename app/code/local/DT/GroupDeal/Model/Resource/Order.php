<?php
/**
 * Created by JetBrains PhpStorm.
 * User: DUCTHANG
 * Date: 10/21/14
 * Time: 11:17 PM
 * To change this template use File | Settings | File Templates.
 */
class DT_GroupDeal_Model_Resource_Order extends Mage_Core_Model_Resource_Db_Abstract
{
    protected   function _construct()
    {
        $this->_init('dt_groupdeal/order', 'entity_id');
    }
}