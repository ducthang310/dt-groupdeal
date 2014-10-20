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

    public function getTierPrice() {
        if (!$this->getData('tier_price') && $this->getId()) {var_dump($this->getReadConnection());die();
            $condition = $this->getReadConnection()->quoteInto('group_deal_id =?', $this->getId());
            $select = $this->getReadConnection()->select()
                ->from($this->getTable('dt_groupdeal/tierprice'), array('tier_id', 'tier_qty', 'tier_price'))
                ->where($condition);
            $this->setData('tier_price', $this->getReadConnection()->fetchAll($select));
        }
        return $this;
    }
}