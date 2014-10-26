<?php
/**
 * Created by JetBrains PhpStorm.
 * User: DUCTHANG
 * Date: 10/26/14
 * Time: 9:08 AM
 * To change this template use File | Settings | File Templates.
 */
class DT_GroupDeal_Block_Adminhtml_Deal_Edit_Tabs_Renderer_Price extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $price = Mage::helper('core')->currency($row->getCurrentPrice(), true, false);
        return '<span class="dt-deal-item-price"><span>' . $price . '</span></span>';
    }
}