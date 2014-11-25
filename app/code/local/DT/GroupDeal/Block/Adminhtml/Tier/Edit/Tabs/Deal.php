<?php
/**
 * Created by JetBrains PhpStorm.
 * User: DUCTHANG
 * Date: 11/23/14
 * Time: 4:41 PM
 * To change this template use File | Settings | File Templates.
 */
class DT_GroupDeal_Block_Adminhtml_Tier_Edit_Tabs_Deal extends Mage_Adminhtml_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('dt/product/deal.phtml');
    }

    /**
     * Retrieve currently edited product object
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        return Mage::registry('current_product');
    }

    /**
     * Retrieve deal of product
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getDeal()
    {
        $id = $this->getProduct()->getDealId();
        if ($id) {
            $deal = Mage::getModel('dt_groupdeal/deal')->load($id);
            if ($deal->getId()) {
                return $deal;
            }
        }
        return false;
    }

    /**
     * Retrieve tier of product
     *
     * @return Mage_Catalog_Model_Product
     */
    public function getTier()
    {
        if ($this->getDeal()) {
            $tier = Mage::getModel('dt_groupdeal/tier')->load($this->getDeal()->getTierId());
            if ($tier->getId()) {
                return $tier;
            }
        }
        return false;
    }
}