<?php
/**
 * Created by JetBrains PhpStorm.
 * User: DucThang
 * Date: 10/25/14
 * Time: 6:17 PM
 */
class DT_GroupDeal_Block_Adminhtml_Deal_Edit_Tabs_Renderer_Status extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        if ($row->getIsActive()) {
            $result = Mage::helper('dt_groupdeal')->checkDealTime($row);
        } else {
            $result['status'] = 'disabled';
        }
        return '<span class="dt-deal-item-' . $result['status'] . '"><span>' . $result['status'] . '</span></span>';
    }
}