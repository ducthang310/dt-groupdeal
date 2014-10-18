<?php
/**
 * Created by   : JetBrains PhpStorm.
 * @project     : dt-groupdeal
 * @author      : DUC THANG
 * @Date        : 10/13/14
 * @Time        : 11:22 PM
 * @copyright  Copyright (c) 2014
 *
 */
class DT_GroupDeal_Block_Adminhtml_Deal_Edit_Tabs_Renderer_Tier
    extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Price_Group_Abstract
{

    /**
     * Initialize block
     */
    public function __construct()
    {
        $this->setTemplate('dt/groupdeal/edit/renderer/tier.phtml');
    }

    /**
     * Retrieve list of initial customer groups
     *
     * @return array
     */
    protected function _getInitialCustomerGroups()
    {
        return array(Mage_Customer_Model_Group::CUST_GROUP_ALL => Mage::helper('catalog')->__('ALL GROUPS'));
    }

    /**
     * Sort values
     *
     * @param array $data
     * @return array
     */
    protected function _sortValues($data)
    {
        usort($data, array($this, '_sortTierPrices'));
        return $data;
    }

    /**
     * Sort tier price values callback method
     *
     * @param array $a
     * @param array $b
     * @return int
     */
    protected function _sortTierPrices($a, $b)
    {
        if ($a['tier_qty'] != $b['tier_qty']) {
            return $a['tier_qty'] < $b['tier_qty'] ? -1 : 1;
        }

        return 0;
    }

    /**
     * Prepare global layout
     * Add "Add tier" button to layout
     *
     * @return Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Price_Tier
     */
    protected function _prepareLayout()
    {
        $button = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
            'label' => Mage::helper('catalog')->__('Add Tier'),
            'onclick' => 'return tierPriceControl.addItem()',
            'class' => 'add'
        ));
        $button->setName('add_tier_price_item_button');

        $this->setChild('add_button', $button);
        return parent::_prepareLayout();
    }

    /**
     * Prepare group price values
     *
     * @return array
     */
    public function getValues()
    {
        $values = array();
        $data = $this->getElement()->getValue();

        if (is_array($data)) {
            $values = $this->_sortValues($data);
        }

        return $values;
    }
}
