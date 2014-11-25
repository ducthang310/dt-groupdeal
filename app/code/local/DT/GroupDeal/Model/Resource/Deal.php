<?php
/**
 * Created by   : JetBrains PhpStorm.
 * @project     : dt-groupdeal
 * @author      : DUC THANG
 * @Date        : 10/7/14
 * @Time        : 11:00 PM
 * @copyright  Copyright (c) 2014
 *
 */
class DT_GroupDeal_Model_Resource_Deal extends Mage_Core_Model_Resource_Db_Abstract
{
    protected   function _construct()
    {
        $this->_init('dt_groupdeal/deal', 'group_deal_id');
    }

    /**
     * Perform actions after object load
     *
     * @param Varien_Object $object
     * @return Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        $_dealFromDate = new Zend_Date($object->getDealFromDate(), 'Y-M-d h:m:s', null);
        $object->setDealFromDate($_dealFromDate->toString('M/d/yy h:mm a'));
        $_dealToDate = new Zend_Date($object->getDealToDate(), 'Y-M-d h:m:s', null);
        $object->setDealToDate($_dealToDate->toString('M/d/yy h:mm a'));
        return $this;
    }

    /**
     * Perform actions before object save
     *
     * @param Varien_Object $object
     * @return Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        $_dealFromDate = new Zend_Date($object->getDealFromDate(), 'M/d/yy h:mm a', null);
        $object->setDealFromDate($_dealFromDate->toString('Y-M-d H:m:s'));
        $_dealToDate = new Zend_Date($object->getDealToDate(), 'M/d/yy h:mm a', null);
        $object->setDealToDate($_dealToDate->toString('Y-M-d H:m:s'));
        return parent::_beforeSave($object);
    }
}