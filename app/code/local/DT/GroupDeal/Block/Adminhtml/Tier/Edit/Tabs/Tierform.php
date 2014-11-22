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
class DT_GroupDeal_Block_Adminhtml_Tier_Edit_Tabs_Tierform extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        // initial form
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $tierData = Mage::registry('current_tier');

        $fieldset = $form->addFieldset('dt_form', array('legend' => Mage::helper('dt_groupdeal')->__('Tier information')));

        $fieldset->addField('tier_name', 'text', array(
            'label' => Mage::helper('dt_groupdeal')->__('Name'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'tier_name',
        ));

        $fieldset->addField('tier_price', 'text', array(
            'label' => Mage::helper('dt_groupdeal')->__('Tier Price'),
            'class'=> 'required-entry',
            'required' => true,
            'name' => 'tier_price',
            'value' => $tierData->getData('tier_price')
        ));

        $form->getElement('tier_price')->setRenderer(
            $this->getLayout()->createBlock('dt_groupdeal/adminhtml_tier_edit_tabs_renderer_price')
        );

        if (Mage::getSingleton('adminhtml/session')->getTierData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getTierData());
            Mage::getSingleton('adminhtml/session')->setTierData(null);
        } elseif (Mage::registry('current_tier')) {
            $form->setValues(Mage::registry('current_tier')->getData());
        }

        return parent::_prepareForm();

    }
}