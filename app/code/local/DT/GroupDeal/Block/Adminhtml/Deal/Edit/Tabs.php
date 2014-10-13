<?php
/**
 * Created by   : JetBrains PhpStorm.
 * @project     : dt-groupdeal
 * @author      : DUC THANG
 * @Date        : 10/12/14
 * @Time        : 2:41 PM
 * @copyright  Copyright (c) 2014
 *
 */
class DT_GroupDeal_Block_Adminhtml_Deal_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('deal_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('dt_groupdeal')->__('Deal'));
    }

    protected function _beforeToHtml() {
        $this->addTab('general_section', array(
            'label' => $this->__('General Information'),
            'title' => $this->__('General Information'),
            'content' => $this->getLayout()->createBlock('dt_groupdeal/adminhtml_deal_edit_tabs_dealform')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }

}