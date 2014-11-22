<?php
/**
 * Created by JetBrains PhpStorm.
 * User: DucThang
 * Date: 11/22/14
 * Time: 4:10 PM
 */
class DT_GroupDeal_Block_Adminhtml_Tier extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_tier';
        $this->_blockGroup = 'dt_groupdeal';
        $this->_headerText = Mage::helper('dt_groupdeal')->__('Group Tier Manager');
        $this->_addButtonLabel = Mage::helper('dt_groupdeal')->__('Add Tier');
        parent::__construct();
    }
}