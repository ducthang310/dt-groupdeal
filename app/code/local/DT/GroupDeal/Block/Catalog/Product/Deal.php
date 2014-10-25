<?php
/**
 * Created by JetBrains PhpStorm.
 * User: DUCTHANG
 * Date: 10/19/14
 * Time: 10:25 AM
 * To change this template use File | Settings | File Templates.
 */
class DT_GroupDeal_Block_Catalog_Product_Deal extends Mage_Core_Block_Template
{
    protected $_groupDeal;

    protected function _construct()
    {
        parent::_construct();
        if ($this->checkInDealTime()) {
            $this->_groupDeal = Mage::registry('dt_deal_' . $this->getProduct()->getId());
        }

    }
    /**
     * Retrieve current product model
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        return Mage::registry('product');
    }

    public function getTierPrice() {
        if ($this->_groupDeal) {
            return $this->_groupDeal->getTierPrice();
        }
    }

    public function checkInDealTime() {
        return Mage::helper('dt_groupdeal')->checkProductInDeal($this->getProduct());
    }
}