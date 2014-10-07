<?php
/**
 * Created by   : JetBrains PhpStorm.
 * @project     : dt-groupdeal
 * @author      : DUC THANG
 * @Date        : 10/7/14
 * @Time        : 9:34 PM
 * @copyright  Copyright (c) 2014
 *
 */
class DT_GroupDeal_Block_Adminhtml_Deal extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_deal';
        $this->_blockGroup = 'dt_groupdeal';
        $this->_headerText = Mage::helper('dt_groupdeal')->__('Group Deal Manager');
        $this->_addButtonLabel = Mage::helper('dt_groupdeal')->__('Add Deal');
        parent::__construct();
    }
}