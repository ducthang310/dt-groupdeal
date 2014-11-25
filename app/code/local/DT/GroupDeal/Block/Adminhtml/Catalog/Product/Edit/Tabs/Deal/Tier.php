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
            $data = $this->getTier()->getData('tier_price');

            if (is_array($data)) {
                $values = $this->_sortValues($data);
            }
            return $values;
        }
        return false;
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
}