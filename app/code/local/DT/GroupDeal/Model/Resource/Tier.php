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
class DT_GroupDeal_Model_Resource_Tier extends Mage_Core_Model_Resource_Db_Abstract
{
    protected   function _construct()
    {
        $this->_init('dt_groupdeal/tier', 'tier_id');
    }

    /**
     * Perform actions after object load
     *
     * @param Varien_Object $object
     * @return Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        $object->setTierPrice(Mage::helper('core')->jsonDecode($object->getTierPrice()));
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
        $object->setTierPrice(Mage::helper('core')->jsonEncode($object->getTierPrice()));
        return parent::_beforeSave($object);
    }
}