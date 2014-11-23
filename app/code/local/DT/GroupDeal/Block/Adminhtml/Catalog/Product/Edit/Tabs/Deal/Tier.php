<?php
/**
 * Created by JetBrains PhpStorm.
 * User: DUCTHANG
 * Date: 11/24/14
 * Time: 12:12 AM
 * To change this template use File | Settings | File Templates.
 */
class DT_GroupDeal_Block_Adminhtml_Catalog_Product_Edit_Tabs_Deal_Tier extends Mage_Adminhtml_Block_Template
{
    /**
     * Initialize block
     */
    public function __construct()
    {
        $this->setTemplate('dt/product/tier.phtml');
    }

    /**
     * Get tier price
     */
    public function getValues()
    {
        if ($this->getTier()) {
            $values = array();
            $data = Mage::helper('core')->jsonDecode($this->getTier()->getData('tier_price'));

            if (is_array($data)) {
                $values = $this->_sortValues($data);
            }
            return $values;
        }
        return false;
    }
}